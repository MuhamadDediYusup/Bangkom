<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use App\Models\LMS\Category;
use App\Models\LMS\Course;
use App\Models\LMS\Enrollment;
use App\Models\LMS\RequestAccess;
use App\Repositories\Interfaces\CoursesInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        // jumlah kursus berdasarkan kategori
        $courseByCartegory = Course::select('category_id', \DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->get();

        // total kursus
        $totalCourse = Course::count();

        // 4 kursus terbaru
        $latestCourse = Course::orderBy('entry_time', 'desc')->with('category')->limit(5)->get();

        // 5 kursus terpopuler berdasarkan jumlah peserta dari lms_enrollments atau model Entollment
        $popularCourse = Course::withCount('enrollments')->orderBy('enrollments_count', 'desc')->limit(5)->get();

        // Jumlah Kursus
        $totalCourse = Course::count();

        // Jumlah Kategori
        $totalCategory = Category::count();

        // Jumlah Pengguna Terdaftar di Enrollments berdasarkan nip
        $totalUserCourse = Enrollment::select('nip')->distinct()->count();

        // jumlah Pengguna Meminta Request
        $totalRequest = RequestAccess::where('status', '1')->count();

        // My Course
        $myCourse = app(CoursesInterface::class)->getMyCourse(auth()->user()->user_id)->get();

        $data = [
            'title' => 'Dashboard',
            'data' => 'dashboard',
            'courseByCategory' => $courseByCartegory,
            'totalCourse' => $totalCourse,
            'latestCourse' => $latestCourse,
            'popularCourse' => $popularCourse,
            'totalCourse' => $totalCourse,
            'totalCategory' => $totalCategory,
            'totalUserCourse' => $totalUserCourse,
            'totalRequest' => $totalRequest,
            'myCourse' => $myCourse
        ];

        return view('lms.index', $data);
    }
}
