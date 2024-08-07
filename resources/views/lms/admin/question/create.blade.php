@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')
<form id="questionsForm" method="POST" action="{{ route('lms.admin.question.store', $quiz->quiz_id) }}"
    enctype="multipart/form-data">
    @csrf
    <div id="questionsContainer">
        <input type="hidden" name="quiz_id" value="{{ $quiz->quiz_id }}" id="">
        @foreach($questions as $index => $question)
        <div class="card mb-3 question" data-question-id="{{ $question->id }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="question-number">Pertanyaan #{{ $index + 1 }}</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="question_text_{{ $index }}">Teks Pertanyaan</label>
                    <input type="text" class="form-control" id="question_text_{{ $index }}"
                        value="{{ $question->question_text }}" placeholder="Teks Pertanyaan">
                </div>
                <div class="form-row align-items-end">
                    <div class="form-group col-md-8">
                        <label for="question_type_{{ $index }}">Tipe Pertanyaan</label>
                        <select class="form-control" id="question_type_{{ $index }}">
                            <option value="multiple_choice" @if($question->question_type == 'multiple_choice') selected
                                @endif>Pilihan Ganda</option>
                            <option value="true_false" @if($question->question_type == 'true_false') selected
                                @endif>Benar/Salah</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="question_image_{{ $index }}">Tambahkan Gambar</label>
                        <input type="file" class="form-control-file" id="question_image_{{ $index }}">
                        @if($question->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$question->image) }}" alt="Gambar Pertanyaan"
                                class="img-fluid" style="max-height: 100px; object-fit: cover;">
                        </div>
                        @endif
                    </div>
                </div>
                <div class="options">
                    @foreach($question->options as $optionIndex => $option)
                    <div class="form-group option">

                        <div class="flex-grow-1 mr-3">
                            <label for="option_text_{{ $index }}_{{ $optionIndex }}">Pilihan {{ $optionIndex + 1
                                }}</label>
                            <div class="row">
                                <div class="col-md-11">
                                    <input type="text" class="form-control"
                                        id="option_text_{{ $index }}_{{ $optionIndex }}"
                                        value="{{ $option->option_text }}" placeholder="Teks Pilihan">
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input is_correct_radio"
                                            id="is_correct" value="{{ $optionIndex }}" @if($option->is_correct) checked
                                        @endif>
                                        <label class="custom-control-label" for="is_correct">Benar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-primary mb-md-0 mb-sm-2" id="addQuestionBtn">Tambah Pertanyaan</button>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    let questionIndex = {{ $questions->count() }};

    function updateQuestionNumbers() {
        document.querySelectorAll('#questionsContainer .question').forEach((question, index) => {
            question.querySelector('.question-number').innerText = `Pertanyaan #${index + 1}`;
        });
    }

    document.getElementById('addQuestionBtn').addEventListener('click', function () {
        let questionContainer = document.createElement('div');
        questionContainer.classList.add('card', 'mb-3', 'question');
        questionContainer.dataset.questionId = `new_${questionIndex}`;

        questionContainer.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="question-number badge badge-primary">Pertanyaan #${questionIndex + 1}</span>
                <button type="button" class="btn btn-danger btn-sm deleteQuestionBtn">Hapus Pertanyaan</button>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="question_text_${questionIndex}">Teks Pertanyaan</label>
                    <input type="text" class="form-control" id="question_text_${questionIndex}" name="questions[new_${questionIndex}][question_text]" placeholder="Teks Pertanyaan" required>
                </div>
                <div class="row d-flex align-items-center">
                    <div class="form-group col-md-8">
                        <label for="question_type_${questionIndex}">Tipe Pertanyaan</label>
                        <select class="form-control" id="question_type_${questionIndex}" name="questions[new_${questionIndex}][question_type]" required>
                            <option value="multiple_choice">Pilihan Ganda</option>
                            <option value="true_false">Benar/Salah</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="question_image_${questionIndex}">File</label>
                        <input type="file" class="form-control" id="question_image_${questionIndex}" name="questions[new_${questionIndex}][image]">
                    </div>
                </div>

                <div class="options"></div>
                <button type="button" class="btn btn-success btn-sm addOptionBtn">Tambah Pilihan</button>
            </div>
        `;

        document.getElementById('questionsContainer').appendChild(questionContainer);
        questionIndex++;
        updateQuestionNumbers();
    });

    document.getElementById('questionsContainer').addEventListener('click', function (event) {
        if (event.target.classList.contains('addOptionBtn')) {
            let questionContainer = event.target.closest('.question');
            let optionsContainer = questionContainer.querySelector('.options');
            let optionIndex = optionsContainer.children.length;
            let questionId = questionContainer.dataset.questionId || `new_${questionIndex}`;

            let optionContainer = document.createElement('div');
            optionContainer.classList.add('form-group', 'option');
            optionContainer.innerHTML = `
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-grow-1 mr-3">
                        <label for="option_text_${questionId}_${optionIndex}">Pilihan ${optionIndex + 1}</label>
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="option_text_${questionId}_${optionIndex}"
                                        name="questions[${questionId}][options][${optionIndex}][option_text]" placeholder="Teks Pilihan" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input is_correct_radio" id="is_correct_${questionId}_${optionIndex}"
                                        name="questions[${questionId}][is_correct]" value="${optionIndex}" required>
                                    <label class="custom-control-label" for="is_correct_${questionId}_${optionIndex}">Benar</label>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm deleteOptionBtn ml-2">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            optionsContainer.appendChild(optionContainer);
        }

        if (event.target.classList.contains('deleteQuestionBtn')) {
            if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                event.target.closest('.question').remove();
                updateQuestionNumbers();
            }
        }

        if (event.target.classList.contains('deleteOptionBtn')) {
            event.target.closest('.option').remove();
        }
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        let isValid = true;
        let questions = document.querySelectorAll('#questionsContainer .question');

        questions.forEach(question => {
            let correctOptions = question.querySelectorAll('input.is_correct_radio:checked');
            if (correctOptions.length !== 1) {
                isValid = false;
                alert(`Setiap pertanyaan harus memiliki satu dan hanya satu opsi yang benar. Periksa ${question.querySelector('.question-number').innerText}.`);
            }
        });

        if (!isValid) {
            event.preventDefault();
        }
    });

    updateQuestionNumbers();
});

</script>

@endsection
