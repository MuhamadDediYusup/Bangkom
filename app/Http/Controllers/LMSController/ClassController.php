<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Certificate;
use App\Models\LMS\Course;
use App\Models\LMS\LessonStatus;
use App\Models\LMS\Quiz;
use App\Models\LMS\QuizResult;
use App\Models\PegawaiModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pegawai;
use Peopleaps\Scorm\Model\ScormModel;
use PhpParser\Node\Stmt\Catch_;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClassController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        // cek user_id apakah sudah terdaftar di course
        $this->middleware(function ($request, $next) {
            $courseSlug = $request->route('course_slug');

            $course = Course::where('slug', $courseSlug)->with('enrollments')->firstOrFail();

            try {
                $course = Course::where('slug', $courseSlug)->with('enrollments')->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->back()->with('error', 'Kursus tidak ditemukan.');
            }

            $userId = Auth::id();

            // Check if user is enrolled in the course
            if (!$course->enrollments || $course->enrollments->count() === 0) {
                return redirect()->back()->with('error', 'Anda belum terdaftar di kursus ini.');
            }

            return $next($request);
        });
    }

    public function index($course_slug, $module_id, $lesson_id)
    {
        try {
            $course = Course::with(['detail_course', 'modules' => function ($query) {
                $query->orderBy('module_chapter');
            }, 'modules.lessons' => function ($query) {
                $query->orderBy('lesson_chapter');
            }])
                ->where('slug', $course_slug)
                ->firstOrFail();

            $certificate = Certificate::where('user_id', Auth::user()->user_id)
                ->where('course_id', $course->course_id)
                ->first();

            $modules = $course->modules->sortBy('module_chapter');
            $module = $modules->firstWhere('module_id', $module_id);
            if (!$module) {
                throw new \Exception("Modul tidak ditemukan.");
            }

            $lessons = $module->lessons->sortBy('lesson_chapter');
            $lesson = $lessons->firstWhere('lesson_id', $lesson_id);
            if (!$lesson) {
                throw new \Exception("Materi tidak ditemukan.");
            }

            $previousCompleted = true;
            $previousModuleLastLessonCompleted = true;
            $moduleIndex = $modules->search(function ($mod) use ($module_id) {
                return $mod->module_id == $module_id;
            });

            // Cek apakah modul sebelumnya telah diselesaikan
            if ($moduleIndex > 0) {
                $previousModule = $modules[$moduleIndex - 1];
                $lastLessonOfPreviousModule = $previousModule->lessons->sortBy('lesson_chapter')->last();
                $previousModuleLastLessonCompleted = LessonStatus::isCompleted(Auth::user()->user_id, $lastLessonOfPreviousModule->lesson_id);
                if (!$previousModuleLastLessonCompleted) {
                    return redirect()->back()->with('error', 'Maaf, Anda belum bisa membuka modul ini. Mohon pastikan semua modul sebelumnya (termasuk submission/quiz) sudah diselesaikan.');
                }
            }

            // Cek apakah semua materi sebelumnya di modul ini telah diselesaikan
            foreach ($lessons as $modLesson) {
                if ($modLesson->lesson_chapter >= $lesson->lesson_chapter) {
                    break;
                }
                if (!LessonStatus::isCompleted(Auth::user()->user_id, $modLesson->lesson_id)) {
                    $previousCompleted = false;
                    break;
                }
            }

            if (!$previousCompleted) {
                return redirect()->back()->with('error', 'Maaf, Anda belum bisa membuka modul ini. Mohon pastikan semua modul sebelumnya (termasuk submission/quiz) sudah diselesaikan.');
            }

            // Cari previous lesson dalam modul yang sama terlebih dahulu
            $previousLesson = $lessons->filter(function ($l) use ($lesson) {
                return $l->lesson_chapter < $lesson->lesson_chapter;
            })->last();

            // Jika tidak ada previous lesson dalam modul yang sama, cari di modul sebelumnya
            $previousModuleId = null;
            if (!$previousLesson && $moduleIndex > 0) {
                $previousModule = $modules[$moduleIndex - 1];
                $previousLesson = $previousModule->lessons->sortBy('lesson_chapter')->last();
                $previousModuleId = $previousModule->module_id;
            }

            if ($lesson->content_type == 'quiz') {
                $quiz = Quiz::where('lesson_id', $lesson->lesson_id)->with('questions', 'results')->first();
            }

            if ($lesson->content_type == 'scorm') {
                $dataScorm = ScormModel::where('uuid', $lesson->content_url)->first();
            }

            $data = [
                'course' => $course,
                'module' => $module,
                'lesson' => $lesson,
                'previous_lesson' => $previousLesson,
                'previous_module' => $previousModuleId,
                'moduleIndex' => $moduleIndex,
                'quiz' => $quiz ?? null,
                'certificate' => $certificate ?? null,
                'dataScorm' => $dataScorm ?? null
            ];

            return view('lms.class.index', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan.');
        }
    }

    public function checkedLessonStatus($course_slug, $module_id, $lesson_id)
    {
        $course = Course::with(['detail_course', 'modules' => function ($query) {
            $query->orderBy('module_chapter');
        }, 'modules.lessons' => function ($query) {
            $query->orderBy('lesson_chapter');
        }])
            ->where('slug', $course_slug)
            ->firstOrFail();

        $modules = $course->modules->sortBy('module_chapter');
        $module = $modules->firstWhere('module_id', $module_id);
        $lessons = $module->lessons->sortBy('lesson_chapter');
        $lesson = $lessons->firstWhere('lesson_id', $lesson_id);

        if ($lesson->content_type == 'quiz') {
            $quiz = Quiz::where('lesson_id', $lesson->lesson_id)->with('questions', 'results')->first();
            $quizResult = QuizResult::where('quiz_id', $quiz->quiz_id)
                ->where('user_id', Auth::user()->user_id)
                ->where('passed', 1)
                ->firstOr(function () use ($quiz) {
                    return QuizResult::where('quiz_id', $quiz->quiz_id)
                        ->where('user_id', Auth::user()->user_id)
                        ->first();
                });

            if (!$quizResult) {
                return redirect()->back()->with('error', 'Anda belum menyelesaikan kuis ini.');
            } else if ($quizResult->passed == 0) {
                return redirect()->back()->with('error', 'Maaf, Anda belum lulus kuis ini.');
            }
        }

        $userId = auth()->user()->user_id;

        $existingStatus = $lesson->lessonStatuses()
            ->where('user_id', $userId)
            ->where('lesson_id', $lesson_id)
            ->first();

        if (!$existingStatus) {
            $lesson->lessonStatuses()->firstOrCreate(
                ['user_id' => $userId],
                [
                    'lesson_id' => $lesson_id,
                    'is_completed' => 1,
                    'completed_at' => now(),
                    'entry_user' => $userId
                ]
            );
        }

        $nextLesson = $lessons->filter(function ($l) use ($lesson) {
            return $l->lesson_chapter > $lesson->lesson_chapter;
        })->first();

        if ($nextLesson) {
            return redirect()->route('lms.class.index', [
                'course_slug' => $course_slug,
                'module_id' => $module_id,
                'lesson_id' => $nextLesson->lesson_id
            ]);
        }

        $nextModule = $modules->filter(function ($m) use ($module) {
            return $m->module_chapter > $module->module_chapter;
        })->first();

        if ($nextModule) {
            $firstLessonOfNextModule = $nextModule->lessons->sortBy('lesson_chapter')->first();
            return redirect()->route('lms.class.index', [
                'course_slug' => $course_slug,
                'module_id' => $nextModule->module_id,
                'lesson_id' => $firstLessonOfNextModule->lesson_id
            ]);
        }

        return redirect()->route('lms.class.index', [
            'course_slug' => $course_slug,
            'module_id' => $module_id,
            'lesson_id' => $lesson_id
        ]);
    }

    public function generateCertificate($course_slug, $module_id, $lesson_id)
    {
        $user = Auth::user();
        $user = PegawaiModel::where('nip', $user->user_id)->firstOrFail();

        $course = Course::where('slug', $course_slug)->with('detail_course')->firstOrFail();

        // Check if the certificate already exists
        $certificate = Certificate::where('user_id', $user->nip)
            ->where('course_id', $course->course_id)
            ->first();
        if ($certificate) {
            return redirect()->back()->with('error', 'Sertifikat sudah pernah di-generate.');
        }

        $certificateFileName = 'certificate_' . $user->nip . '_' . $course->course_id . '.pdf';
        $certificatePath = 'files/certificates/' . $certificateFileName;
        $qrCodeFileName = 'qr_' . $user->nip . '_' . $course->course_id . '.png';
        $qrCodePath = 'files/qrcodes/' . $qrCodeFileName;

        // Generate the complete text for the QR code
        $verificationText = "R. Budi Pramono, S.IP, M.Si\nNIP. 196906121998031016\nVerifikasi : ";
        $url = asset($certificatePath);
        $qrCodeData = $verificationText . $url;

        // Generate QR Code with the text and the URL
        $qrCodeResult = Builder::create()
            ->writer(new PngWriter())
            ->data($qrCodeData)
            ->build();
        $qrCodeImage = $qrCodeResult->getString();

        // Save the QR code image to the public directory
        file_put_contents(public_path($qrCodePath), $qrCodeImage);

        // Ensure the QR code path is correct for the template
        $publicQrCodePath = asset($qrCodePath);

        // Generate PDF Certificate with landscape orientation
        $pdf = PDF::loadView('lms.certificates.template', [
            'user' => $user,
            'course' => $course,
            'qrCodePath' => $publicQrCodePath
        ])->setPaper('a4', 'landscape');

        // Save the PDF file to the public directory
        file_put_contents(public_path($certificatePath), $pdf->output());

        // Save Certificate to Database
        Certificate::create([
            'user_id' => $user->nip,
            'course_id' => $course->course_id,
            'certificate_file' => $certificateFileName,
            'entry_user' => $user->nip,
        ]);

        // Debug view certificate with QR code
        // return $pdf->stream();

        return redirect()->back()->with('success', 'Sertifikat berhasil di-generate.');
    }
}
