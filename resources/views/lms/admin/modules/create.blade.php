@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.module.store') }}" method="POST" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Kursus</label>
                                <input type="text" id="course_name" name="course_name" class="form-control" required
                                    value="{{ $course->course_name }}" readonly>
                                <input type="hidden" id="course_id" name="course_id" class="form-control" required
                                    value="{{ $course->course_id }}">
                                <input type="hidden" id="slug" name="slug" class="form-control" required
                                    value="{{ $course->slug }}">
                            </div>
                            <div class="form-group">
                                <label>Module BAB</label>
                                <input type="number" id="module_chapter" name="module_chapter" class="form-control"
                                    required value="{{ old('module_chapter') ?? $last_chapter+1 }}">
                                <small class="form-text text-muted">Bab terakhir pada kursus ini :
                                    <span class="font-weight-bold">Bab-{{ $last_chapter }}</span></small>

                                @error('module_chapter')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Module Name</label>
                                <input type="text" id="module_name" name="module_name" class="form-control" required
                                    value="{{ old('module_name') }}">
                                @error('module_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Estimasi Waktu</label>
                                <input type="number" id="estimated_time" name="estimated_time" class="form-control"
                                    required value="{{ old('estimated_time') }}">
                                @error('estimated_time')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Module</label>
                                <textarea name="description" id="description" class="form-control summernote-simple"
                                    required value="">{{ old('description') }}</textarea>
                                @error('module_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Module</button>
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