@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert')

<!-- Main Content -->
<section class="section">
    <div class="mb-4">
        <div class="hero text-white hero-bg-image hero-bg-parallax"
            style="background-image: url({{ url('course/flyer/' . $course->img_flyer) }});">
            <div class="hero-inner">
                <h2>{{ $course->course_name }} - {{ $course->detail_course->total_hours }} JP</h2>
                <p class="lead">{{ $course->description }}</p>
                <div class="mt-4">
                    @if ($course->modules->first())
                    @if (empty($course->enrollments))

                    @if (isset($course->request_access))
                    <span class="badge badge-info">Anda sudah mengajukan pendaftaran,
                        silahkan menunggu konfirmasi dari admin.</span>
                    @else
                    <p>
                        <span>Anda belum terdaftar pada kursus ini, silahkan melakukan pendaftaran dengan memasukkan
                            token atau melakukan pengajuan pendaftaran : </span>
                    </p>

                    <button class="btn btn-outline-white" id="btnToken">Daftar Dengan Token</button>
                    <button class="btn btn-outline-warning" id="btnPendaftaran">Ajukan Pendaftaran</button>

                    @endif

                    @else
                    @php
                    $module_id = $course->modules->first()->module_id;
                    $lesson_id = $course->modules->first()->lessons->first()->lesson_id;

                    if ($lastCompletedLessonStatus) {
                    $module_id = $lastCompletedLessonStatus->lesson->module_id;
                    $lesson_id = $lastCompletedLessonStatus->lesson_id;
                    }
                    @endphp


                    <a href="{{ route('lms.class.index', [
                                $course->slug,
                                $module_id,
                                $lesson_id
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


    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary text-dark">
                <div class="card-body">
                    <h2 class="section-title font-weight-bold">Tentang Kelas</h2>
                    {!! $course->detail_course->detail_course !!}
                    <h2 class="section-title font-weight-bold">Target Peserta</h2>
                    {!! $course->detail_course->target_participants !!}
                    <h2 class="section-title font-weight-bold">Tujuan Kelas</h2>
                    {!! $course->detail_course->objectives !!}
                    <h2 class="section-title font-weight-bold">Kompetensi</h2>
                    {!! $course->detail_course->competence !!}
                    <h2 class="section-title font-weight-bold">Tanggal Pelaksanaan</h2>
                    Kursus dilaksanakan pada tanggal : {!! $course->detail_course->start_date !!} Sampai {!!
                    $course->detail_course->end_date !!}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary text-black">
                <div class="card-body">
                    <h2 class="section-title font-weight-bold mb-4">Daftar Materi</h2>

                    @if ($course->modules->first())
                    <div id="accordion">
                        <div class="accordion">
                            @foreach ($course->modules as $module)
                            <div class="accordion-header" role="button" data-toggle="collapse"
                                data-target="#panel-body-{{ $module->module_id }}" aria-expanded="true">
                                <h4>{{ $module->module_chapter }}. {{ $module->module_name }} ({{
                                    $module->estimated_time }} Menit)</h4>
                            </div>
                            <div class="accordion-body collapse show" id="panel-body-{{ $module->module_id }}"
                                data-parent="#accordion">
                                <ul class="list-unstyled">
                                    @foreach ($module->lessons as $lesson)
                                    <li>
                                        <a href="#">{{ $lesson->lesson_name }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <p>Belum ada materi yang tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@if (empty($course->enrollments))
<!-- Bootstrap Modal -->
<div class="modal fade" id="tokenModal" tabindex="-1" role="dialog" aria-labelledby="tokenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('lms.course.enroll.token') }}" method="POST">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tokenModalLabel">Masukkan Token | {{ $course->course_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="tokenInput" name="token"
                        placeholder="Masukkan token untuk mendaftar" oninput="this.value = this.value.toUpperCase()">
                    <input type="hidden" name="course_id" value="{{ $course->course_id }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitToken">Daftar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@if (empty($course->enrollments))
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    // Script untuk menampilkan modal token
    document.getElementById('btnToken').addEventListener('click', function() {
        $('#tokenModal').modal('show');
    });

    var course_name = "{{ $course->course_name }}";

    // Script untuk SweetAlert konfirmasi pendaftaran
    document.getElementById('btnPendaftaran').addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin akan melakukan pendaftaran pada kursus "+course_name+" ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Saya yakin!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('lms.course.enroll.request', ['course_id' => $course->course_id]) }}";
            }
        });
    });

    // Ensure modals are appended to body
    $('#tokenModal').appendTo("body");
</script>
@endpush
@endif