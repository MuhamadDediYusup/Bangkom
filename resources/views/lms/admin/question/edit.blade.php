@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')
<form id="questionsForm" method="POST" action="{{ route('lms.admin.question.update', $quiz->quiz_id) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="questionsContainer">
        <input type="hidden" name="quiz_id" value="{{ $quiz->quiz_id }}">
        @foreach($questions as $index => $question)
        <div class="card mb-3 question" data-question-id="{{ $question->id }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="question-number">Pertanyaan #{{ $index + 1 }}</span>
                <button type="button" class="btn btn-danger btn-sm delete-question-btn">Hapus Pertanyaan</button>
            </div>
            <div class="card-body">
                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                <input type="hidden" name="questions[{{ $index }}][delete]" class="delete-question-input" value="0">
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
                        <div class="flex-grow-1 mr-3">
                            <label for="option_text_{{ $index }}_{{ $optionIndex }}">Pilihan {{ $optionIndex + 1
                                }}</label>
                            <div class="row">
                                <div class="col-md-11">
                                    <input type="text" class="form-control"
                                        id="option_text_{{ $index }}_{{ $optionIndex }}"
                                        name="questions[{{ $index }}][options][{{ $optionIndex }}][option_text]"
                                        value="{{ $option->option_text }}" placeholder="Teks Pilihan">
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input is_correct_radio"
                                            id="is_correct_{{ $index }}_{{ $optionIndex }}"
                                            name="questions[{{ $index }}][is_correct]" value="{{ $optionIndex }}"
                                            @if($option->is_correct) checked @endif>
                                        <label class="custom-control-label"
                                            for="is_correct_{{ $index }}_{{ $optionIndex }}">Benar</label>
                                        <button type="button"
                                            class="btn btn-danger btn-sm delete-option-btn ml-2">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                            value="{{ $option->id }}">
                        <input type="hidden" name="questions[{{ $index }}][options][{{ $optionIndex }}][delete]"
                            class="delete-option-input" value="0">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-question-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const card = this.closest('.question');
            card.remove();
        });
    });

    document.querySelectorAll('.delete-option-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const option = this.closest('.option');
            option.remove();
        });
    });
});
</script>
@endsection