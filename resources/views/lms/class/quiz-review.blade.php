@extends('lms.layout.quiz')

@section('content')
<nav class="navbar bg-light">
    <div class="container-fluid py-2">
        <a class="btn text-danger"
            href="{{ route('lms.class.quiz.show', ['quiz_id' => $quiz->quiz_id, 'question_index' => 0]) }}"><i
                class="bi bi-x-lg"></i> Kembali ke Kuis</a>
        <div class="card px-4 py-2 fw-bold">
            07 Menit : 44 Detik
        </div>
    </div>
</nav>
<hr class="mt-0">
<div>
    <div class="class-container__content quiz-container-content">
        <div class="row m-0 justify-content-between quiz-container d-flex">
            <div class="quiz-indicator-questions">
                <p class="mt-lg-3 fw-bold text-secondary">
                    {{ $quiz->quiz_name }}
                </p>
                <hr class="mb-0">
                <div class="px-1 py-3 d-flex flex-wrap">
                    @forelse($questions as $index => $question)
                    @php
                    $answered = $question->answered ?? false;
                    @endphp
                    <a class="btn {{ $answered ? 'btn-success' : 'btn-danger' }} m-1"
                        href="{{ route('lms.class.quiz.show', ['quiz_id' => $quiz->quiz_id, 'question_index' => $index]) }}">
                        {{ $index + 1 }}
                    </a>
                    @empty
                    <p>Belum ada soal.</p>
                    @endforelse
                </div>

                <div class="card me-md-3 border-0 shadow-sm ">
                    <div class="card-body">
                        <span class="badge text-bg-success">Sudah dijawab</span>
                        <span class="badge text-bg-danger">Belum dijawab</span>
                        <span class="badge text-bg-primary">Soal Saat Ini</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 quiz-area">
                <div class="my-4 academy-tutorial-content content--prettify-light">
                    @forelse($questions as $index => $question)
                    <div class="my-4 fit-content">
                        <span class="badge rounded-pill text-bg-secondary mb-2">Soal {{ $index + 1 }} dari {{
                            count($questions) }}</span>

                        <div class="fr-view dcd-multiple-choice__question">
                            <p>{{ $question->question_text }}</p>
                        </div>
                        <div class="my-4 fit-content">
                            @forelse($question->options as $option)
                            <div class="mb-2">
                                <input name="option_id" id="answer-{{ $option->option_id }}"
                                    value="{{ $option->option_id }}" type="radio" class="dcd-custom-radio" {{
                                    isset($answers[$question->question_id]) && $answers[$question->question_id] ==
                                $option->option_id ? 'checked' : '' }} disabled>
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
                        <hr>
                    </div>
                    @empty
                    <p>Belum ada soal.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection