@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.quiz.update', $quiz->quiz_id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Kursus</label>
                                <input type="text" id="course_name" name="course_name" class="form-control"
                                    value="{{ $course->course_name }}" readonly>
                                <input type="hidden" id="course_id" name="course_id" class="form-control"
                                    value="{{ $course->course_id }}">
                                <input type="hidden" id="slug" name="slug" class="form-control"
                                    value="{{ $course->slug }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Kuis</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Nama Pelajaran</label>
                                        <select id="lesson_id" name="lesson_id" class="form-control">
                                            <option value="">Pilih Pelajaran</option>
                                            @foreach ($lesson as $item)
                                            <option value="{{ $item->lesson_id }}" {{ old('lesson_id') ?? $quiz->
                                                lesson_id == $item->lesson_id ? 'selected' : '' }}>{{
                                                $item->lesson_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Kuis</label>
                                        <input type="text" id="quiz_name" name="quiz_name" class="form-control" required
                                            value="{{ old('quiz_name') ?? $quiz->quiz_name }}">
                                        @error('quiz_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <input type="text" id="description" name="description" class="form-control"
                                            value="{{ old('description') ?? $quiz->description }}" required></input>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Durasi Quiz</label>
                                        <input type="text" id="duration" name="duration" class="form-control" required
                                            value="{{ old('duration') ?? $quiz->duration }}">
                                        @error('duration')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail Pembobotan</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Skor Minimal</label>
                                        <input type="number" id="passing_score" name="passing_score"
                                            class="form-control" required
                                            value="{{ old('passing_score') ?? $quiz->passing_score }}" min="0"
                                            max="100">
                                        @error('passing_score')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Bobot Kuis</label>
                                        <input type="number" id="weight_percentage" name="weight_percentage"
                                            class="form-control" required min="0" max="100"
                                            value="{{ old('weight_percentage') ?? $quiz->weight_percentage }}">
                                        @error('weight_percentage')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Soal</label>
                                        <input type="number" id="question_row" name="question_row" class="form-control"
                                            required value="{{ old('question_row') ?? $quiz->question_row }}" min="0">
                                        @error('question_row')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Kuis</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('css')
<link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endpush