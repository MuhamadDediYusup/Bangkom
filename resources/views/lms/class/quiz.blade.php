@extends('lms.layout.quiz')

@section('content')
<nav class="navbar bg-light">
    <div class="container-fluid py-2">
        <!-- Tombol Akhiri Kuis -->
        <form method="POST" action="{{ route('lms.class.quiz.finish', ['quiz_id' => $quiz->quiz_id]) }}"
            id="end-quiz-form">
            @csrf
            <button type="submit" class="btn text-danger">
                <i class="bi bi-x-lg"></i> Akhiri Kuis
            </button>
        </form>
        <div class="card px-4 py-2 fw-bold" id="timer">
            <!-- Timer will be updated by JavaScript -->
        </div>
    </div>
</nav>
<hr class="mt-0">
<div>
    <div class="class-container__content quiz-container-content">
        <div class="row m-0 justify-content-between quiz-container d-flex">
            <div class="quiz-indicator-questions">
                <div class="mb-md-2">
                    <p class="mt-lg-3 fw-bold text-secondary">{{ $quiz->quiz_name }}</p>
                    <hr class="mb-0">
                    <div class="px-1 py-3 d-flex flex-wrap">
                        @foreach(range(1, $totalQuestions) as $index)
                        @php
                        // Determine if the question has been answered
                        $questionId = $questions->get($index - 1)->question_id;
                        $answered = in_array($questionId, array_keys(Session::get('quiz_answers', [])));
                        $isCurrent = $index - 1 == $question_index; // Check if this is the current question
                        @endphp

                        <a class="btn {{ $isCurrent ? 'btn-primary' : ($answered ? 'btn-success' : 'btn-danger') }} m-1"
                            href="{{ route('lms.class.quiz.show', ['quiz_id' => $quiz->quiz_id, 'question_index' => $index - 1]) }}">
                            {{ $index }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{--
                <hr>
                <span class="badge text-bg-success">Sudah dijawab</span>
                <br>
                <span class="badge text-bg-danger">Belum dijawab</span>
                <br>
                <span class="badge text-bg-primary">Soal Saat Ini</span>
                <hr> --}}
            </div>

            <div class="col-lg-7 quiz-area">
                <div class="my-4 academy-tutorial-content content--prettify-light">
                    <span class="badge rounded-pill text-bg-secondary mb-2">Soal {{ $question_index + 1 }} dari {{
                        $totalQuestions }}</span>
                    <div class="fr-view dcd-multiple-choice__question">
                        <p>{{ $currentQuestion->question_text ?? 'Soal tidak ditemukan' }}</p>
                    </div>
                    <div class="my-4 fit-content">
                        <form method="POST" id="quiz-form"
                            action="{{ $question_index < $totalQuestions - 1 ? route('lms.class.quiz.storeAnswer', ['quiz_id' => $quiz->quiz_id, 'question_index' => $question_index]) : route('lms.class.quiz.finish', ['quiz_id' => $quiz->quiz_id]) }}">
                            @csrf
                            <div id="answer-options">
                                @forelse($currentQuestion->options as $option)
                                <div class="mb-2">
                                    <input name="option_id" id="answer-{{ $option->option_id }}"
                                        value="{{ $option->option_id }}" type="radio" class="dcd-custom-radio" {{
                                        (Session::get('quiz_answers')[$currentQuestion->question_id] ?? null) ==
                                    $option->option_id ? 'checked' : '' }}>
                                    <label for="answer-{{ $option->option_id }}"
                                        class="ml-3 fr-view dcd-multiple-choice__answer">
                                        <span>
                                            <p>{{ $option->option_text }}</p>
                                        </span>
                                    </label>
                                </div>
                                @empty
                                <p>Opsi jawaban tidak tersedia.</p>
                                @endforelse
                            </div>
                            <input type="hidden" name="question_id" id="question-id"
                                value="{{ $currentQuestion->question_id }}">

                            <hr>
                            <div class="d-flex flex-row justify-content-between quiz-area_buttons py-4 my-2">
                                @if($question_index > 0)
                                <a class="btn fw-bold text-secondary"
                                    href="{{ route('lms.class.quiz.show', ['quiz_id' => $quiz->quiz_id, 'question_index' => $question_index - 1]) }}">
                                    <i class="bi bi-arrow-left"></i>
                                    <span>Sebelumnya</span>
                                </a>
                                @endif
                                <button type="submit" class="btn fw-bold text-secondary">
                                    {{ $question_index < $totalQuestions - 1 ? 'Selanjutnya' : 'Selesai & Simpan' }} <i
                                        class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let duration = {{ $remainingTime }}; // Sisa waktu dalam detik
        const timerElement = document.getElementById('timer');
        const form = document.getElementById('quiz-form');
        const endQuizForm = document.getElementById('end-quiz-form');

        let timer = setInterval(function() {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;

            timerElement.textContent = `${minutes} Menit : ${seconds < 10 ? '0' + seconds : seconds} Detik`;

            if (duration <= 180) {
                timerElement.classList.add('text-danger');
            } else {
                timerElement.classList.remove('text-danger');
                timerElement.classList.add('text-warning');
            }

            if (duration <= 0) {
                clearInterval(timer);
                console.log('Waktu habis, menampilkan konfirmasi...');

                // Menampilkan SweetAlert konfirmasi
                Swal.fire({
                    title: 'Waktu Habis',
                    text: 'Waktu kuis telah habis. Jawaban akan disimpan dan Anda akan diarahkan ke halaman awal.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Mengirimkan form setelah konfirmasi...');

                        // Menyembunyikan SweetAlert dan submit form
                        Swal.fire({
                            title: 'Mengirimkan Jawaban...',
                            text: 'Harap tunggu sebentar.',
                            icon: 'info',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Set action form untuk mengarahkan ke finish
                            form.action = "{{ route('lms.class.quiz.finish', ['quiz_id' => $quiz->quiz_id]) }}";
                            form.submit();
                        });
                    }
                });
            }

            duration--;
        }, 1000);

        // Handle end quiz form submission
        endQuizForm.addEventListener('submit', function() {
            if (duration > 0) {
                // Stop the countdown
                clearInterval(timer);
                console.log('Kuis diakhiri sebelum waktu habis.');
            }
        });
    });
</script>
@endpush