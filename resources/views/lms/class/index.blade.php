@extends('lms.layout.class')

@section('content')
@include('lms.partials.alert')

<div class="section-body">
    <div class="card-title">
        <h2 class="section-title font-weight-bold mb-4">{{ $module->module_name }} | {{ $lesson->lesson_name }}</h2>
    </div>
    @if(!empty($lesson->content_url))
    <div class="card card-primary">
        @if ($lesson->content_type == 'video')
        <iframe class="responsive-iframe" id="youtubeVideo"
            src="https://www.youtube.com/embed/{{ $lesson->content_url }}?autoplay=1&amp;?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
            allowfullscreen allowtransparency allow="autoplay" frameborder="0"></iframe>
        @elseif($lesson->content_type == 'pdf')
        <div class="position-relative">
            <iframe class="responsive-iframe pdf" src="{{ asset('files/lessons/' . $lesson->content_url) }}"
                frameborder="0" allowfullscreen allowtransparency></iframe>
            <button id="fullscreenBtn" class="btn btn-primary position-absolute" style="bottom: 10px; right: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-fullscreen" viewBox="0 0 16 16">
                    <path
                        d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5M.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5" />
                </svg> Lihat Layar Penuh
            </button>
        </div>
        @elseif($lesson->content_type == 'url')
        <iframe class="responsive-iframe" src="{{ $lesson->content_url }}" frameborder="0" allowfullscreen
            allowtransparency></iframe>

        @elseif($lesson->content_type == 'powerpoint')
        <iframe class="responsive-iframe pdf" src="{!! $lesson->content_url !!}" allowfullscreen allowtransparency
            frameborder="0"></iframe>
        @elseif($lesson->content_type == 'scorm')
        <!-- Tambahkan player SCORM -->
        <div class="position-relative">
            <iframe class="responsive-iframe scorm"
                src="{{ asset('files/scorm/' . $dataScorm->uuid . '/' . $dataScorm->entry_url) }}" frameborder="0"
                allowfullscreen allowtransparency allow="autoplay">
            </iframe>
        </div>
        @endif
    </div>
    @if ($lesson->content != null)
    <div class="card-title">
        <h2 class="section-title font-weight-bold mb-4">Deskripsi Materi</h2>
    </div>
    @endif
    @endif

    <div class="card card-primary">
        <div class="card-body">
            @if ($lesson->content_type == 'quiz')
            {{-- Quiz landing page --}}
            <div class="mb-md-5">
                <h4 class="card-title text-center">Aturan Kuis/Ujian</h4>
                <hr>
                <p class="card-text text-center">Kuis/Ujian <strong>{{ $lesson->lesson_name }} - {{
                        $quiz->quiz_name }}</strong> berfungsi untuk menguji pengetahuan Anda tentang materi yang telah
                    dipelajari di modul ini.</p>

                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-graduation-cap"></i> Syarat nilai kelulusan:</span>
                                <span><strong>{{ $quiz->passing_score }}%</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock"></i> Durasi ujian:</span>
                                <span><strong>{{ $quiz->duration }} menit</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-question-circle"></i> Jumlah soal:</span>
                                <span><strong>{{ $quiz->questions->count() }} soal</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if ($quiz->results->count() > 0)
                <div class="card card-primary mt-4 mx-md-4">
                    <div class="card-body">
                        <h5 class="card-title">Riwayat Pengerjaan Kuis</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover font-weight-bold">
                                <thead>
                                    <tr>
                                        <th scope="col" width="2%">#</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col" class="text-center">Nilai</th>
                                        <th scope="col" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quiz->results as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y (h:i)', strtotime($item->completed_at)) }}</td>
                                        <td class="text-center">{{ $item->score }}</td>
                                        <td class="text-center">{!! $item->passed ? '<span
                                                class="badge badge-success">Lulus</span>' : '<span
                                                class="badge badge-danger">Tidak Lulus</span>' !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @php
                $maxAttempts = 3;
                $shortWaitTime = 5 * 60; // 5 minutes in seconds
                $longWaitTime = 2 * 60 * 60; // 2 hours in seconds

                $showStartQuizButton = !$quiz->results->contains('passed', true);
                $attemptCount = $quiz->results->count();
                $waitTime = $attemptCount < $maxAttempts ? $shortWaitTime : $longWaitTime; $countDownTime=0; if
                    ($attemptCount> 0) {
                    $lastQuizResult = $quiz->results->last();
                    $nextAttemptTime = strtotime($lastQuizResult->completed_at . " +{$waitTime} seconds");
                    $now = strtotime(date('Y-m-d H:i:s'));
                    $countDownTime = $nextAttemptTime - $now;
                    }
                    @endphp

                    @if ($showStartQuizButton)
                    @if ($attemptCount > 0)
                    @if ($countDownTime > 0)
                    <div class="alert alert-info mt-4 mx-md-4" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Perhatian!</h5>
                        <hr class="mt-1 mb-2" style="border-top: 2px solid white;">
                        <p>Anda hanya diperbolehkan mengerjakan kuis sebanyak {{ $maxAttempts }} kali.
                            Jika Anda tidak lulus pada percobaan ketiga, Anda harus menunggu selama 2 jam untuk
                            mengulang pengerjaan kuis kembali.</p>
                        <p>Waktu tunggu: <span class="badge badge-primary" id="countdown">{{ gmdate('H:i:s',
                                $countDownTime) }}</span></p>
                    </div>
                    @else
                    <div class="text-center mt-4">
                        <button class="btn btn-success btn-lg" id="start-quiz-btn"><i class="fas fa-play"></i> Mulai
                            Kuis</button>
                    </div>
                    @endif
                    @else
                    <div class="text-center mt-4">
                        <button class="btn btn-success btn-lg" id="start-quiz-btn"><i class="fas fa-play"></i> Mulai
                            Kuis</button>
                    </div>
                    @endif
                    @endif

            </div>
            @else
            {!! $lesson->content !!}
            @endif
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center mb-3">
            @if ($previous_lesson)
            <a href="{{ route('lms.class.index', [$course->slug, $previous_module ?? $module->module_id, $previous_lesson->lesson_id]) }}"
                type="button" class="btn btn-primary mb-md-0 mb-2"><i class="fas fa-arrow-left"></i> Materi
                Sebelumnya</a>
            @endif

            <a href=" {{ route('lms.class.checked', [$course->slug, $module->module_id, $lesson->lesson_id]) }}"
                type="button" class="btn btn-primary">
                @inject('lessonStatus', 'App\Models\LMS\LessonStatus')
                @if ($lessonStatus::isCompleted(Auth::user()->user_id, $lesson->lesson_id))
                Materi Selanjutnya <i class="fas fa-arrow-right"></i>
                @else
                Tandai Selesai <i class="fas fa-check"></i>
                @endif
            </a>
        </div>
    </div>
</div>

@if ($module->isLast() && $lesson->isLast())
<div class="card">
    <div class="card-body">
        @if (!$certificate)

        <p class="font-weight-bold">Selamat telah berhasil menyelesaikan Kursus {{ $course->course_name }}, Untuk
            mencetak
            sertifikat silahkan
            klik pada tombol Generate Sertifikat dibawah ini :</p>

        <form
            action="{{ route('lms.class.generateCertificate', ['course_slug' => $course->slug, 'module_id' => $module->module_id, 'lesson_id' => $lesson->lesson_id]) }}"
            method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Generate Sertifikat</button>
        </form>
        @else

        <div class="card">
            <div class=" card-title">
                <h2 class="section-title font-weight-bold mb-4">Detail Sertifikat</h2>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Dicetak pada : {{ $certificate->issued_at->format('d-m-Y (h:i)') }}
                </li>
                <li class="list-group-item">Sertifikat diterbitkan oleh : BKPP Kabupaten Sleman</li>
                </li>
            </ul>
        </div>

        <a href="{{ asset('files/certificates/' . $certificate->certificate_file) }}" class="btn btn-success"
            target="_blank">Lihat Sertifikat</a>
        @endif
    </div>
</div>
@endif

@php
session(['last_visited_lesson' => [$course->slug, $module->module_id, $lesson->lesson_id,
substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5)]]);
@endphp
@endsection

@if ($lesson->content_type == 'quiz')
@push('js')
<script>
    $(document).ready(function() {
    if ($('#start-quiz-btn').length) {
        $('#start-quiz-btn').on('click', function() {
            Swal.fire({
                title: 'Konfirmasi',
                html: `
                    <div class="alert alert-warning" role="alert" style="text-align: left;">
                        <strong>Perhatian:</strong>
                        <ul style="margin-top: 10px; text-align: left;">
                            <li>Pastikan Anda memiliki koneksi internet yang stabil selama mengerjakan ujian.</li>
                            <li>Jika waktu habis, jawaban Anda akan otomatis tersimpan dan ujian akan berakhir.</li>
                            <li>Apabila tidak memenuhi syarat kelulusan, Anda harus menunggu selama 5 menit untuk mengulang pengerjaan ujian kembali.</li>
                            <li>Baca setiap soal dengan seksama sebelum menjawab.</li>
                        </ul>
                    </div>
                    <p style="margin-top: 10px; font-weight: bold;">Selamat Mengerjakan!</p>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Mulai Kuis',
                cancelButtonText: 'Batalkan',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.getContent().addEventListener('click', () => {
                        Swal.showValidationMessage('Silakan konfirmasi atau batalkan untuk melanjutkan.');
                    });
                }
            }).then((result) => {
                if (result.value) {
                    window.location.href = "{{ route('lms.class.quiz.show', $quiz->quiz_id) }}";
                }
            });
        });
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            var countdownTime = {{ $countDownTime }};
            var countdownInterval = setInterval(function () {
                if (countdownTime <= 0) {
                    clearInterval(countdownInterval);
                    location.reload(); // Reload the page when the countdown reaches zero
                } else {
                    countdownTime--;
                    var minutes = Math.floor(countdownTime / 60);
                    var seconds = countdownTime % 60;
                    countdownElement.textContent = ('0' + minutes).slice(-2) + ' menit ' + ('0' + seconds).slice(-2) + ' detik';
                }
            }, 1000);
        }
    });
</script>

@endpush
@endif

@push('js')
<script>
    $('#fullscreenModal').on('shown.bs.modal', function() {
        $(this).find('.modal-dialog').css('margin', '0');
        $(this).find('.modal-content').css('height', '100vh');
        $(this).find('.modal-body').css('height', '100vh');
    });

    $(document).ready(function(){
        $('#fullscreenBtn').on('click', function() {
            $('#fullscreenModal').modal('show');
            toggleFullScreen();
        });

        $('#fullscreenModal').on('hidden.bs.modal', function() {
            toggleFullScreen();
        });
    });

    function toggleFullScreen() {
        var elem = document.body;
        var isInFullScreen = (document.fullScreenElement && document.fullScreenElement!== null) ||
            (document.mozFullScreen || document.webkitIsFullScreen);

        if (isInFullScreen) {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        } else {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        }
    }
</script>

<style>
    .fullscreen-modal .modal-dialog {
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
        max-width: none;
    }

    .fullscreen-modal .modal-content {
        height: 100%;
        border: none;
        border-radius: 0;
        display: flex;
        flex-direction: column;
    }

    .fullscreen-modal .modal-body {
        flex: 1;
        padding: 0;
        overflow: hidden;
    }
</style>

<div class="modal fade fullscreen-modal" id="fullscreenModal" tabindex="-1" aria-labelledby="fullscreenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                @if ($lesson->content_type == 'pdf')
                <object data="{{ asset('files/lessons/' . $lesson->content_url) }}" type="application/pdf" width="100%"
                    height="100%">
                    <p>Browser Anda tidak mendukung tampilan PDF.</p>
                </object>
                @endif
            </div>
            <div class="position-absolute" style="bottom: 0; right: 0; padding: 10px;">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="bi bi-fullscreen-exit"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-fullscreen-exit" viewBox="0 0 16 16">
                        <path
                            d="M5.5 0a.5.5 0 0 1 .5.5v4A1.5 1.5 0 0 1 4.5 6h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5m5 0a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 10 4.5v-4a.5.5 0 0 1 .5-.5M0 10.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 6 11.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5m10 1a1.5 1.5 0 0 1 1.5-1.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0z" />
                    </svg> Keluar Layar Penuh
                </button>
            </div>
        </div>
    </div>
</div>
@endpush