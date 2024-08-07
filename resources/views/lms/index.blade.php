@extends('lms.layout.main')

@section('content')
<div class="section-header">
    <h1>Dashboard</h1>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="far fa-user"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Kursus</h4>
                </div>
                <div class="card-body">
                    {{ $totalCourse }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="far fa-newspaper"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Kategori</h4>
                </div>
                <div class="card-body">
                    {{ $totalCategory }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="far fa-file"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Peserta Kursus</h4>
                </div>
                <div class="card-body">
                    {{ $totalUserCourse }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Request Pendaftaran</h4>
                </div>
                <div class="card-body">
                    {{ $totalRequest }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kursus di Ikuti</h4>
                <div class="card-header-action">
                    <a href="{{ route('lms.course.mycourse') }}" class="btn btn-primary">Lihat Selengkapnya <i
                            class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <div class="card-body py-0 px-4">
                <ul class="list-group list-group-flush px-0">
                    @foreach ($myCourse as $item)
                    <li class="list-group-item">{{ $item->course_name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Jumlah Kursus Berdasarkan Kategori</h4>
            </div>
            <div class="card-body">

                @foreach ($courseByCategory as $item)
                <div class="mb-4">
                    <div class="text-small float-right font-weight-bold text-muted">{{ $item->total }}</div>
                    <div class="font-weight-bold mb-1">{{ $item->category->category_name }}</div>
                    <div class="progress" data-height="3">
                        <div class="progress-bar" role="progressbar" data-width="{{ $item->total }}%"
                            aria-valuenow="{{ $item->total }}" aria-valuemin="0" aria-valuemax="{{ $totalCourse }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kursus Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="summary">
                    @foreach ($latestCourse->take(1) as $course)
                    <div>
                        <div class="hero text-white hero-bg-image hero-bg-parallax"
                            style="background-image: url({{ url('course/flyer/' . $course->img_flyer) }});">
                            <div class="hero-inner">
                                <h2><a href="{{ route('lms.course.show', $course->slug) }}" class="text-white">{{
                                        $course->course_name }} - {{
                                        $course->detail_course->total_hours }}
                                        JP</a></h2>
                                <p title="{{ $course->description }}" data-toggle="tooltip" data-placement="bottom"
                                    data-original-title="{{ $course->description }}">
                                    {{ \Illuminate\Support\Str::limit($course->description, 150) }}
                                </p>
                                <div class="mt-4">
                                    @if ($course->modules->first())
                                    @if (empty($course->enrollments))

                                    @if (isset($course->request_access))
                                    <span class="badge badge-info">Anda sudah mengajukan pendaftaran,
                                        silahkan menunggu konfirmasi dari admin.</span>
                                    @else
                                    <p>
                                        <span>Anda belum terdaftar pada kursus ini, silahkan melakukan pendaftaran
                                            dengan memasukkan
                                            token atau melakukan pengajuan pendaftaran : </span>
                                    </p>

                                    <button class="btn btn-outline-white" id="btnToken">Daftar Dengan Token</button>
                                    <button class="btn btn-outline-warning" id="btnPendaftaran">Ajukan
                                        Pendaftaran</button>

                                    @endif

                                    @else
                                    <a href="{{ route('lms.class.index', [
                                        $course->slug,
                                        $course->modules->first()->module_id,
                                        $course->modules->first()->lessons->first()->lesson_id
                                    ]) }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                        Mulai Belajar
                                    </a>
                                    @endif
                                    @else
                                    <span class="badge badge-pill badge-danger">Belum ada materi yang tersedia.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="summary-item">
                        <h6>Item List <span class="text-muted">(3 Items)</span></h6>
                        @foreach ($latestCourse->skip(1) as $item)
                        <ul class="list-unstyled list-unstyled-border">
                            <li class="media">
                                <a href="{{ route('lms.course.show', $item->slug) }}">
                                    <img class="mr-3 rounded" width="50" height="50"
                                        src="{{ url('course/flyer/' . $item->img_flyer) }}" alt="product">
                                </a>
                                <div class="media-body">
                                    <div class="media-right">
                                        <div class="font-weight-600 text-muted text-small">Kursus Terbaru</div>
                                    </div>
                                    <div class="media-title"><a data-toggle="tooltip" data-placement="bottom"
                                            data-original-title="{{ $course->description }}"
                                            href="{{ route('lms.course.show', $item->slug) }}">{{
                                            $item->course_name }}</a></div>
                                    <div class="text-muted text-small">Kategori : <a
                                            href="{{ route('lms.course.category', $item->category->slug) }}">{{
                                            $item->category->category_name
                                            }}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection