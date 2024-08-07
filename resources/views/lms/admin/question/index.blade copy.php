@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
<form id="questionsForm" method="POST" action="{{ route('lms.admin.question.store', $quiz_id) }}"
    enctype="multipart/form-data">
    @csrf
    <div id="questionsContainer">
        @foreach($questions as $index => $question)
        <div class="card mb-3 question" data-question-id="{{ $question->id }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="question-number">Pertanyaan #{{ $index + 1 }}</span>
                <button type="button" class="btn btn-danger btn-sm deleteQuestionBtn">Hapus Pertanyaan</button>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="question_text_{{ $index }}">Teks Pertanyaan</label>
                    <input type="text" class="form-control" id="question_text_{{ $index }}"
                        name="questions[{{ $index }}][question_text]" value="{{ $question->question_text }}"
                        placeholder="Teks Pertanyaan">
                </div>
                <div class="form-row align-items-end">
                    <div class="form-group col-md-8">
                        <label for="question_type_{{ $index }}">Tipe Pertanyaan</label>
                        <select class="form-control" id="question_type_{{ $index }}"
                            name="questions[{{ $index }}][question_type]">
                            <option value="multiple_choice" @if($question->question_type == 'multiple_choice') selected
                                @endif>Pilihan Ganda</option>
                            <option value="true_false" @if($question->question_type == 'true_false') selected
                                @endif>Benar/Salah</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="question_image_{{ $index }}">Tambahkan Gambar</label>
                        <input type="file" class="form-control-file" id="question_image_{{ $index }}"
                            name="questions[{{ $index }}][image]">
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
                        <label for="option_text_{{ $index }}_{{ $optionIndex }}">Pilihan {{ $optionIndex + 1 }}</label>
                        <input type="text" class="form-control" id="option_text_{{ $index }}_{{ $optionIndex }}"
                            name="questions[{{ $index }}][options][{{ $optionIndex }}][option_text]"
                            value="{{ $option->option_text }}" placeholder="Teks Pilihan">
                        <div class="form-check mt-2">
                            <input type="radio" class="form-check-input is_correct"
                                name="questions[{{ $index }}][is_correct]" value="{{ $optionIndex }}"
                                @if($option->is_correct) checked @endif>
                            <label class="form-check-label"
                                for="is_correct_{{ $index }}_{{ $optionIndex }}">Benar</label>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm deleteOptionBtn mt-2">Hapus Pilihan</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-success btn-sm addOptionBtn">Tambah Pilihan</button>
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
    questionContainer.dataset.questionId = '';

    questionContainer.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="question-number">Pertanyaan #${questionIndex + 1}</span>
            <button type="button" class="btn btn-danger btn-sm deleteQuestionBtn">Hapus Pertanyaan</button>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="question_text_${questionIndex}">Teks Pertanyaan</label>
                <input type="text" class="form-control" id="question_text_${questionIndex}" name="questions[new_${questionIndex}][question_text]" placeholder="Teks Pertanyaan">
            </div>
            <div class="row d-flex align-items-center">
                <div class="form-group col-md-8">
                    <label for="question_type_${questionIndex}">Tipe Pertanyaan</label>
                    <select class="form-control" id="question_type_${questionIndex}" name="questions[new_${questionIndex}][question_type]">
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
                    <label for="option_text_${questionId}_${optionIndex}">
                        Pilihan ${optionIndex + 1}
                    </label>
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control" id="option_text_${questionId}_${optionIndex}"
                                    name="questions[${questionId}][options][${optionIndex}][option_text]"
                                    placeholder="Teks Pilihan">
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="is_correct_${questionId}_${optionIndex}"
                                    name="questions[${questionId}][is_correct]" value="${optionIndex}">
                                <label class="custom-control-label" for="is_correct_${questionId}_${optionIndex}">
                                Benar
                                </label>
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

document.getElementById('questionsContainer').addEventListener('change', function (event) {
    if (event.target.classList.contains('is_correct')) {
        let questionContainer = event.target.closest('.question');
        let correctOptions = questionContainer.querySelectorAll('.is_correct:checked');

        if (correctOptions.length > 1) {
            event.target.checked = false;
            alert('Hanya satu jawaban yang benar diperbolehkan per pertanyaan.');
        }
    }
});

updateQuestionNumbers();
});
</script>
@endsection