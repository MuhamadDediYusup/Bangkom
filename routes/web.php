<?php

use App\Http\Controllers\LMSController\AdminCourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModalController;

use App\Http\Controllers\DiklatController;
use App\Http\Controllers\UsulanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JPBangkomController;
use App\Http\Controllers\PendukungController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\MD_PegawaiController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\KompetensiAsnController;
use App\Http\Controllers\CaptchaValidationController;
use App\Http\Controllers\LMSController\AdminCategoryController;
use App\Http\Controllers\LMSController\AdminEnrollmentController;
use App\Http\Controllers\LMSController\AdminLessonController;
use App\Http\Controllers\LMSController\AdminModuleController;
use App\Http\Controllers\LMSController\AdminQuestionController;
use App\Http\Controllers\LMSController\AdminQuizController;
use App\Http\Controllers\LMSController\AdminReqEnrollmentController;
use App\Http\Controllers\LMSController\AdminTokenController;
use App\Http\Controllers\LMSController\ClassController;
use App\Http\Controllers\LMSController\CourseController;
use App\Http\Controllers\LMSController\CourseListController;
use App\Http\Controllers\MD_DiklatTekFungsController;
use App\Http\Controllers\MD_PerangkatDaerahController;
use App\Http\Controllers\MD_DiklatStrukturalController;
use App\Http\Controllers\Rekapitulasi;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\LMSController\DashboardController as LMSDashboardController;
use App\Http\Controllers\LMSController\QuizController;
use App\Http\Controllers\LMSController\ScormController;
use App\Http\Livewire\Lms\Class\Index;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Login Route */

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
// Route::post('/user/delete', [UserController::class, 'destroy'])->name('user.delete');

/* Google Recaptcha */
Route::get('contact-form-captcha', [CaptchaValidationController::class, 'index']);
Route::post('captcha-validation', [CaptchaValidationController::class, 'capthcaFormValidate']);
Route::get('reload-captcha', [CaptchaValidationController::class, 'reloadCaptcha']);
Route::get('/check-session', [LoginController::class, 'checkSession']);

// Rute Session
Route::get('/session/forget/{key}', function ($key) {
    session()->forget($key);
    return response()->json(['status' => 'success']);
})->name('session.forget');


Route::prefix('/')->middleware(['auth'])->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/blank-page', function () {
        $data = [
            'title' => 'Blank Page',
        ];
        return view('blank-page.index', $data);
    })->name('blank-page');

    /* Rekapitulasi */
    Route::get('/rekapitulasi', [DashboardController::class, 'index'])->name('rekapitulasi.index');
    Route::get('/rekapitulasi/login-terbaru', [RekapitulasiController::class, 'loginTerbaru'])->name('rekapitulasi.login_terbaru');
    Route::get('/rekapitulas/login-terbanyak', [RekapitulasiController::class, 'loginTerbanyak'])->name('rekapitulasi.login_terbanyak');
    Route::get('/rekapitulas/aktivitas-laporan', [RekapitulasiController::class, 'aktifitasLaporan'])->name('rekapitulasi.aktivitas_laporan');
    Route::get('/rekapitulasi/laporan/perangkat-daerah', [RekapitulasiController::class, 'lapBerdasarPerangkatDaerah'])->name('rekapitulasi.pd')->middleware('permission:laporan-list');
    Route::get('/rekapitulasi/laporan/asn', [RekapitulasiController::class, 'lapBerdasarAsn'])->name('rekapitulasi.asn')->middleware('permission:laporan-list');
    Route::get('/rekapitulasi/laporan/jenis-diklat', [RekapitulasiController::class, 'lapBerdasarJenisDiklat'])->name('rekapitulasi.jenis_diklat')->middleware('permission:laporan-list');
    Route::get('/rekapitulasi/laporan/waktu', [RekapitulasiController::class, 'lapBerdasarWaktu'])->name('rekapitulasi.waktu')->middleware('permission:usulan-list');
    Route::get('/rekapitulasi/usulan/sumber-dana', [RekapitulasiController::class, 'usulBerdasarSumber'])->name('rekapitulasi.usulan')->middleware('permission:usulan-list');
    Route::get('/rekapitulasi/usulan/jenis-diklat', [RekapitulasiController::class, 'usulBerdasarJenisDiklat'])->name('rekapitulasi.usulan_jenis_diklat')->middleware('permission:usulan-list');

    /* Route Usulan */
    Route::get('/usulan-bangkom', [UsulanController::class, 'index'])->name('usulan_bangkom')->middleware('permission:usulan-list');
    Route::get('/form-usulan', [UsulanController::class, 'form_usulan'])->name('form_usulan')->middleware('permission:usulan-create');
    Route::post('/usulan-bangkom/store', [UsulanController::class, 'store'])->name('usulan_bangkom.store')->middleware('permission:usulan-create');
    Route::get('/usulan-bangkom/{nip}/create', [UsulanController::class, 'create'])->name('usulan_bangkom.create')->middleware('permission:usulan-create');
    Route::get('/update-status/{nip}/{id}', [UsulanController::class, 'update_status'])->name('update_status')->middleware('permission:usulan-edit');
    Route::post('/update-status/edit/{id}', [UsulanController::class, 'edit_status'])->name('update_status.edit')->middleware('permission:usulan-edit');
    Route::post('/usulan-bangkom/destroy/{id}', [UsulanController::class, 'destroy'])->name('usulan_bangkom_destroy')->middleware('permission:usulan-delete');;
    Route::get('/redirect/add-usulan', [UsulanController::class, 'redirectAddUsulan'])->name('usulan.redirect')->middleware('permission:usulan-create');
    Route::get('/usulan/data/ditinjau/{start}/{end}', [UsulanController::class, 'UsulanDitinjau'])->name('usulan.data')->middleware('permission:usulan-list');
    Route::get('/usulan/data/dilaksanakan/{start}/{end}', [UsulanController::class, 'UsulanDilaksanakan'])->name('usulan.data')->middleware('permission:usulan-list');
    Route::get('/usulan/data/disetujui/{start}/{end}', [UsulanController::class, 'UsulanDisetujui'])->name('usulan.data')->middleware('permission:usulan-list');
    Route::get('/usulan/data/ditolak/{start}/{end}', [UsulanController::class, 'UsulandiTolak'])->name('usulan.data')->middleware('permission:usulan-list');
    Route::get('usulan/cetak', [UsulanController::class, 'export'])->name('usulan.cetak')->middleware('permission:export-excel');

    /* Route Pengiriman */
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index')->middleware('permission:pengiriman-list');
    Route::get('/pengiriman/create/{nip}/{id}', [PengirimanController::class, 'create'])->name('pengiriman.create')->middleware('permission:pengiriman-create');
    Route::post('/pengiriman/store', [PengirimanController::class, 'store'])->name('pengiriman.store')->middleware('permission:pengiriman-create');
    Route::get('/pengiriman/edit/{nip}/{id}', [PengirimanController::class, 'edit'])->name('pengiriman.edit')->middleware('permission:pengiriman-edit');
    Route::post('/pengiriman/update/{id}', [PengirimanController::class, 'update'])->name('pengiriman.update')->middleware('permission:pengiriman-edit');
    Route::post('/pengiriman/destroy/{id}', [PengirimanController::class, 'destroy'])->name('pengiriman.destroy')->middleware('permission:pengiriman-delete');
    Route::get('/redirect/add-pengiriman/', [PengirimanController::class, 'redirectAddPengiriman'])->name('pengiriman.redirect')->middleware('permission:pengiriman-create');
    Route::get('pengiriman/cetak', [PengirimanController::class, 'export'])->name('pengiriman.cetak')->middleware('permission:export-excel');
    Route::get('pengiriman/data/dilaksanakan/{start}/{end}', [PengirimanController::class, 'PengirimanDilaksanakan'])->name('pengiriman.data')->middleware('permission:pengiriman-list');
    Route::get('pengiriman/data/selesai/{start}/{end}', [PengirimanController::class, 'PengirimanSelesai'])->name('pengiriman.data')->middleware('permission:pengiriman-list');

    /* Route Laporan */
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index')->middleware('permission:laporan-list');
    Route::get('/laporan/form-laporan', [LaporanController::class, 'form_laporan'])->name('laporan.form_laporan')->middleware('permission:laporan-create');
    Route::post('/laporan/store', [LaporanController::class, 'store'])->name('laporan.store')->middleware('permission:laporan-create');
    Route::get('/laporan/{nip}/{id?}/create', [LaporanController::class, 'create'])->name('laporan.create')->middleware('permission:laporan-create');
    Route::get('/laporan/edit/{nip}/{id}/{status}', [LaporanController::class, 'edit'])->name('laporan.edit')->middleware('permission:laporan-edit');
    Route::post('/laporan/update/{nip}/{id}', [LaporanController::class, 'update'])->name('laporan.update')->middleware('permission:laporan-edit');
    Route::post('/laporan/destroy/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy')->middleware('permission:laporan-delete');
    Route::get('/redirect/add-laporan', [LaporanController::class, 'redirectAddLaporan'])->name('laporan.redirect')->middleware('permission:laporan-create');
    Route::get('/laporan-data/ditinjau/{start}/{end}', [LaporanController::class, 'dataLaporanDitinjau'])->name('laporan.data')->middleware('permission:laporan-list');
    Route::get('/laporan-data/disetujui/{start}/{end}', [LaporanController::class, 'dataLaporanDisetujui'])->name('laporan.data')->middleware('permission:laporan-list');
    Route::get('/laporan-data/diperbaiki/{start}/{end}', [LaporanController::class, 'dataLaporanDiperbaiki'])->name('laporan.data')->middleware('permission:laporan-list');
    Route::get('/laporan-data/ditolak/{start}/{end}', [LaporanController::class, 'dataLaporanDitolak'])->name('laporan.data')->middleware('permission:laporan-list');
    Route::get('/laporan/cetak/{id}', [LaporanController::class, 'export'])->name('laporan.cetak')->middleware('permission:export-excel');

    /* Route Master Data */
    Route::get('/perangkat-daerah', [MD_PerangkatDaerahController::class, 'index'])->name('md_perangkatdaerah')->middleware('permission:master-data');
    Route::get('/diklat-struktural', [MD_DiklatStrukturalController::class, 'index'])->name('md_diklatstruktural')->middleware('permission:master-data');
    Route::get('/diklat-tekfungs', [MD_DiklatTekFungsController::class, 'index'])->name('md_diktekfungs')->middleware('permission:master-data');
    Route::get('/master-pegawai', [MD_PegawaiController::class, 'index'])->name('md_pegawai.index')->middleware('permission:master-data');
    Route::post('/master-pegawai/update', [MD_PegawaiController::class, 'updateDataPegawai'])->name('md_pegawai.update')->middleware('permission:master-data-update');

    /* Dasar Kompetensi */
    Route::get('/daftar-kompetensi', [KompetensiController::class, 'index'])->name('kompetensi.index')->middleware('permission:daftar-kompetensi');
    Route::get('kompetensi/cetak/{id}', [KompetensiController::class, 'export'])->name('kompetensi.cetak')->middleware('permission:export-excel');

    /* Route User And Profile */
    Route::get('profile', [UserController::class, 'detailUser'])->name('user.profile');
    Route::post('profile/update', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    Route::get('/activities', [UserController::class, 'activities'])->name('user.activities');

    Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware('permission:role-list');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:role-create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store')->middleware('permission:role-create');
    Route::get('user/{id}', [UserController::class, 'show'])->name('user.show')->middleware('permission:role-list');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:role-edit');
    Route::put('user/{id}/update', [UserController::class, 'update'])->name('user.update')->middleware('permission:role-edit');
    Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('permission:role-delete');

    /* Route Role */
    Route::get('role', [RoleController::class, 'index'])->name('role.index')->middleware('permission:role-list');
    Route::get('role/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:role-create');
    Route::post('role/store', [RoleController::class, 'store'])->name('role.store')->middleware('permission:role-create');
    Route::get('role/{id}', [RoleController::class, 'show'])->name('role.show')->middleware('permission:role-list');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit')->middleware('permission:role-edit');
    Route::put('role/{id}/update', [RoleController::class, 'update'])->name('role.update')->middleware('permission:role-edit');
    Route::delete('role/{id}', [RoleController::class, 'destroy'])->name('role.destroy')->middleware('permission:role-delete');

    /* Route Permission */
    Route::get('permission', [PermissionController::class, 'index'])->name('permission.index')->middleware('permission:role-list');
    Route::get('permission/create', [PermissionController::class, 'create'])->name('permission.create')->middleware('permission:role-create');
    Route::post('permission/store', [PermissionController::class, 'store'])->name('permission.store')->middleware('permission:role-create');
    Route::get('permission/{id}', [PermissionController::class, 'show'])->name('permission.show')->middleware('permission:role-list');
    Route::get('permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit')->middleware('permission:role-edit');
    Route::put('permission/{id}/update', [PermissionController::class, 'update'])->name('permission.update')->middleware('permission:role-edit');
    Route::delete('permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy')->middleware('permission:role-delete');

    /* Route Chat */
    Route::resource('chat', ChatController::class)->middleware('permission:chat');
    Route::post('chat-message', [ChatController::class, 'chat_message'])->name('chat.message')->middleware('permission:chat');
    Route::post('chat-send', [ChatController::class, 'chat_send'])->name('chat.send')->middleware('permission:chat');
    Route::post('chat-search_user', [ChatController::class, 'chat_search_user'])->name('chat.search_user')->middleware('permission:chat');
    Route::get('chatFrame', [ChatController::class, 'frame'])->name('chat.frame')->middleware('permission:chat');

    /* Route Kompetensi ASN */
    Route::get('/diklat', [DiklatController::class, 'index'])->name('md_diklat.index')->middleware('permission:master-data');
    Route::post('/diklat/update', [DiklatController::class, 'updateDataDiklat'])->name('md_diklat.update')->middleware('permission:master-data-update');
    Route::get('/kompetensi-asn', [KompetensiAsnController::class, 'index'])->name('kompetensiasn.index')->middleware('permission:kompetensi-asn-list');
    Route::get('/kompetensi-asn/edit/{jenis_diklat}/{nip}/{id_diklat}', [KompetensiAsnController::class, 'edit'])->name('kompetensiasn.edit')->middleware('permission:kompetensi-asn-edit');
    Route::post('/kompetensi-asn/update', [KompetensiAsnController::class, 'update'])->name('kompetensiasn.update')->middleware('permission:kompetensi-asn-edit');
    Route::post('/kompetensi-asn/delete/{nip}/{idPendidikan}/{jenisDiklat}', [KompetensiAsnController::class, 'delete'])->name('kompetensiasn.delete')->middleware('permission:kompetensi-asn-delete');
    Route::get('/jp-bangkom', [JPBangkomController::class, 'index'])->name('jpbangkom.index')->middleware('permission:jp-bangkom');
    Route::get('/jp-bangkom/cetak/{pd}/{jam}', [JPBangkomController::class, 'export'])->name('jpbangkom.cetak')->middleware('permission:export-excel');

    /* Modal Detail ASN */
    Route::get('/getDetailAsn/{nip}', [ModalController::class, 'getModalDetailAsn'])->name('modal.modal_detail_asn')->middleware('permission:kompetensi-asn-modal');

    /* Get Jenis Diklat, Rumpun Diklat, Sub Jenis Diklat For Cbb */
    Route::get('/get/sub-jenis-diklat/{jenis_diklat}', [UsulanController::class, 'dataSubJenisDiklat'])->name('usulan.data_sub_jenis_diklat');
    Route::get('/get/rumpun-diklat', [UsulanController::class, 'dataRumpunDiklat'])->name('usulan.data_rumpun_diklat');
    Route::get('/get/sub-jenis-diklat-asn/{jenis_diklat}', [KompetensiAsnController::class, 'dataSubJenisDiklat'])->name('kompetensi_asn.data_sub_jenis_diklat');

    /* Menu Tambahan */
    Route::get('about', [PendukungController::class, 'about'])->name('pendukung.about');
    Route::get('about/edit', [PendukungController::class, 'editAbout'])->name('pendukung.about.edit')->middleware('permission:about-edit');
    Route::post('about/update', [PendukungController::class, 'updateAbout'])->name('pendukung.about.update')->middleware('permission:about-edit');
    Route::get('petunjuk', [PendukungController::class, 'petunjuk'])->name('pendukung.petunjuk');
    Route::get('petunjuk/edit', [PendukungController::class, 'editPetunjuk'])->name('pendukung.petunjuk.edit')->middleware('permission:petunjuk-edit');
    Route::post('petunjuk/update', [PendukungController::class, 'updatePetunjuk'])->name('pendukung.petunjuk.update')->middleware('permission:petunjuk-edit');

    Route::get('get/session', function () {
        return session()->all();
    })->name('get.session');

    // LMS Route
    Route::get('lms', [LMSDashboardController::class, 'index'])->name('lms.index');
    Route::get('lms/my-courses', [CourseController::class, 'myCourse'])->name('lms.course.mycourse');
    Route::get('lms/courses', [CourseController::class, 'allCourse'])->name('lms.course.index');
    Route::get('lms/courses/category/{slug}', [CourseController::class, 'category'])->name('lms.course.category');
    Route::get('lms/courses/{slug}', [CourseController::class, 'show'])->name('lms.course.show');
    Route::post('lms/courses/token/redeem', [CourseController::class, 'enrollToken'])->name('lms.course.enroll.token');
    Route::get('lms/courses/enroll/request', [CourseController::class, 'enrollRequest'])->name('lms.course.enroll.request');

    Route::post('scorm/track/{uuid}', [ScormController::class, 'trackProgress'])->name('scorm.track');

    // LMS Course Classroom | Module
    Route::get('lms/courses/{course_slug}/{module_id}/{lesson_id}', [ClassController::class, 'index'])->name('lms.class.index');
    Route::get('lms/courses/{course_slug}/{module_id}/{lesson_id}/checked', [ClassController::class, 'checkedLessonStatus'])->name('lms.class.checked');

    Route::post('course/{course_slug}/module/{module_id}/lesson/{lesson_id}/generate-certificate', [ClassController::class, 'generateCertificate'])->name('lms.class.generateCertificate');
    Route::get('/certificate/{id}', [CertificateController::class, 'view'])->name('certificate.view');

    Route::post('/quiz/{quiz_id}/submit', [QuizController::class, 'finish'])->name('lms.class.quiz.finish');
    Route::post('/quiz/{quiz_id}/{question_index}', [QuizController::class, 'storeAnswer'])->name('lms.class.quiz.storeAnswer');
    Route::get('/quiz/{quiz_id}/{question_index?}', [QuizController::class, 'show'])->name('lms.class.quiz.show');

    Route::prefix('lms/admin')->name('lms.admin.')->group(function () {
        // Route Management Course
        Route::resource('course', AdminCourseController::class)->names([
            'index' => 'course.index',
            'create' => 'course.create',
            'store' => 'course.store',
            'show' => 'course.show',
            'edit' => 'course.edit',
            'update' => 'course.update',
            'destroy' => 'course.destroy',
        ]);

        // Route Management Category
        Route::resource('category', AdminCategoryController::class)->names([
            'index' => 'category.index',
            'create' => 'category.create',
            'store' => 'category.store',
            'show' => 'category.show',
            'edit' => 'category.edit',
            'update' => 'category.update',
            'destroy' => 'category.destroy',
        ]);

        // Route Management Module
        Route::prefix('module')->name('module.')->group(function () {
            Route::get('create/{course_slug?}', [AdminModuleController::class, 'create'])->name('create');
            Route::get('{id}/edit', [AdminModuleController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminModuleController::class, 'update'])->name('update');
            Route::post('/', [AdminModuleController::class, 'store'])->name('store');
            Route::delete('{id}', [AdminModuleController::class, 'destroy'])->name('destroy');
            Route::get('{course_slug?}', [AdminModuleController::class, 'index'])->name('index');
        });

        // Route Management Lesson
        Route::prefix('lesson')->name('lesson.')->group(function () {
            Route::get('create/{course_slug?}/{module_id?}', [AdminLessonController::class, 'create'])->name('create');
            Route::get('{id}/edit', [AdminLessonController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminLessonController::class, 'update'])->name('update');
            Route::post('/', [AdminLessonController::class, 'store'])->name('store');
            Route::delete('{id}', [AdminLessonController::class, 'destroy'])->name('destroy');
            Route::get('{course_slug?}/{module_id?}', [AdminLessonController::class, 'index'])->name('index');
        });

        // Route Management Quiz
        Route::prefix('quiz')->name('quiz.')->group(function () {
            Route::get('create/{course_slug?}', [AdminQuizController::class, 'create'])->name('create');
            Route::post('/', [AdminQuizController::class, 'store'])->name('store');
            Route::get('{id}/edit', [AdminQuizController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminQuizController::class, 'update'])->name('update');
            Route::delete('{id}', [AdminQuizController::class, 'destroy'])->name('destroy');
            Route::get('{course_slug?}', [AdminQuizController::class, 'index'])->name('index');
        });

        // Route Question Management
        // Route::prefix('question')->name('question.')->group(function () {
        //     Route::get('{quiz_id}', [AdminQuestionController::class, 'index'])->name('index');
        //     Route::post('{quiz_id}', [AdminQuestionController::class, 'store'])->name('store');
        //     Route::put('{quiz_id}/{question_id}', [AdminQuestionController::class, 'update'])->name('update');
        //     Route::delete('{quiz_id}/{question_id}', [AdminQuestionController::class, 'destroy'])->name('destroy');
        // });

        Route::prefix('question')->name('question.')->group(function () {
            Route::get('create/{quiz_id?}', [AdminQuestionController::class, 'create'])->name('create');
            Route::get('{quiz_id}', [AdminQuestionController::class, 'index'])->name('index');
            Route::get('{id}/edit', [AdminQuestionController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminQuestionController::class, 'update'])->name('update');
            Route::post('store', [AdminQuestionController::class, 'store'])->name('store');
            Route::delete('destroy/{id}', [AdminQuestionController::class, 'destroy'])->name('destroy');
            Route::post('import', [AdminQuestionController::class, 'import'])->name('import');
        });

        // Route Management Enrollment
        Route::prefix('enrollment')->name('enrollment.')->group(function () {
            Route::get('create/{course_slug?}', [AdminEnrollmentController::class, 'create'])->name('create');
            Route::post('/', [AdminEnrollmentController::class, 'store'])->name('store');
            Route::get('{id}/edit', [AdminEnrollmentController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminEnrollmentController::class, 'update'])->name('update');
            Route::delete('{id}', [AdminEnrollmentController::class, 'destroy'])->name('destroy');
            Route::get('{course_slug?}', [AdminEnrollmentController::class, 'index'])->name('index');
        });

        // Route Management Token Class
        Route::prefix('token')->name('token.')->group(function () {
            Route::get('create/{course_slug?}', [AdminTokenController::class, 'create'])->name('create');
            Route::post('/', [AdminTokenController::class, 'store'])->name('store');
            Route::get('{id}/edit', [AdminTokenController::class, 'edit'])->name('edit');
            Route::put('{id}', [AdminTokenController::class, 'update'])->name('update');
            Route::delete('{id}', [AdminTokenController::class, 'destroy'])->name('destroy');
            Route::get('{course_slug?}', [AdminTokenController::class, 'index'])->name('index');
        });

        // Route Management Request Access
        Route::prefix('request-access')->name('request-access.')->group(function () {
            Route::get('{course_slug?}', [AdminReqEnrollmentController::class, 'index'])->name('index');
            Route::get('approve/{id}', [AdminReqEnrollmentController::class, 'approve'])->name('approve');
            Route::get('reject/{id}', [AdminReqEnrollmentController::class, 'reject'])->name('reject');
            Route::delete('delete/{id}', [AdminReqEnrollmentController::class, 'destroy'])->name('destroy');
        });

        // GET DATA FOR DATATABLE
        Route::get('categories/get-all-category', [AdminCategoryController::class, 'getCategoryData'])
            ->name('categories.getallcategories');

        Route::get('courses/get-all-course', [AdminCourseController::class, 'getCourseData'])
            ->name('courses.getallcourses');

        Route::get('modules/get-all-module/{slug?}', [AdminModuleController::class, 'getModuleData'])
            ->name('modules.getallmodules');

        Route::get('lessons/get-all-lesson/{slug?}/{module_id?}', [AdminLessonController::class, 'getLessonData'])
            ->name('lesson.getalllessons');

        Route::get('modules/get-module', [AdminModuleController::class, 'getModuleByCourseId'])
            ->name('module.getmodule');

        Route::get('modules/get-module/quiz/{slug?}/{module_id?}', [AdminModuleController::class, 'getModuleByCourseIdQuiz'])
            ->name('module.getmodulequiz');

        Route::get('enrollments/get-all-enrollment/{slug?}', [AdminEnrollmentController::class, 'getEnrollmentData'])
            ->name('enrollment.getallcourses');

        Route::get('enrollments/get-employee', [AdminEnrollmentController::class, 'getEmployeeData'])
            ->name('enrollment.geteployeedata');

        Route::get('tokens/get-all-token/{slug?}', [AdminTokenController::class, 'getTokenData'])
            ->name('token.getalltoken');

        Route::get('request/get-all-request/{slug?}', [AdminReqEnrollmentController::class, 'getReqEnrollmentData'])
            ->name('request.getallrequest');

        Route::get('quizzes/get-all-quiz/{slug?}', [AdminQuizController::class, 'getQuizData'])
            ->name('quiz.getallquiz');


        Route::get('question/get-all-question/{quiz_id?}', [AdminQuestionController::class, 'getAllQuestion'])
            ->name('question.getallquestion');
    });
});
