@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.lesson.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label>Nama Modul</label>
                                <input type="text" id="course_name" name="course_name" class="form-control" required
                                    value="{{ $module->module_name }}" readonly>
                                <input type="hidden" id="module_id" name="module_id" class="form-control" required
                                    value="{{ $module->module_id }}">
                            </div>
                            <div class="form-group">
                                <label>Bab Pelajaran</label>
                                <input type="number" id="lesson_chapter" name="lesson_chapter" class="form-control"
                                    required value="{{ old('lesson_chapter') ?? $last_chapter+1 }}">
                                <small class="form-text text-muted">Bab terakhir pada kursus ini :
                                    <span class="font-weight-bold">Bab-{{ $last_chapter }}</span></small>

                                @error('lesson_chapter')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Nama Pelajaran</label>
                                <input type="text" id="lesson_name" name="lesson_name" class="form-control" required
                                    value="{{ old('lesson_name') }}">
                                @error('lesson_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jenis Pelajaran</label>
                                <select id="content_type" name="content_type" class="form-control"
                                    onchange="handleContentTypeChange()">
                                    <option value="video" {{ old('content_type')=='video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="pdf" {{ old('content_type')=='pdf' ? 'selected' : '' }}>Modul PDF
                                    </option>
                                    <option value="text" {{ old('content_type')=='text' ? 'selected' : '' }}>Konten Teks
                                    </option>
                                    <option value="quiz" {{ old('content_type')=='quiz' ? 'selected' : '' }}>Kuis
                                    </option>
                                    <option value="url" {{ old('content_type')=='url' ? 'selected' : '' }}>Url
                                        (flipbook, etc)</option>
                                    <option value="powerpoint" {{ old('content_type')=='powerpoint' ? 'selected' : ''
                                        }}>Slide Powerpoint</option>
                                    <option value="scorm" {{ old('content_type')=='scorm' ? 'selected' : '' }}>SCORM
                                        Package</option>
                                </select>
                            </div>

                            <div class="form-group" id="url-group">
                                <label id="label-url">Youtube ID</label>
                                <input type="text" id="content_url" name="content_url" class="form-control"
                                    value="{{ old('content_url') }}">
                                @error('content_url')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" id="file-group" style="display:none;">
                                <label>Upload File</label>
                                <input type="file" id="content_file" name="content_file" class="form-control"
                                    accept="application/pdf,application/zip">
                                @error('content_file')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Konten Pelajaran</label>
                                <textarea name="content" id="content"
                                    class="form-control summernote">{{ old('content') }}</textarea>
                                @error('content')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Pelajaran</button>
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

@push('js')
<script>
    function handleContentTypeChange() {
        const contentType = document.getElementById('content_type').value;
        const urlGroup = document.getElementById('url-group');
        const fileGroup = document.getElementById('file-group');
        const contentUrl = document.getElementById('content_url');

        if (contentType === 'video' || contentType === 'url' || contentType === 'powerpoint') {
            if (contentType === 'video') {
                document.getElementById('label-url').innerText = 'Youtube ID';
            } else if (contentType === 'powerpoint') {
                document.getElementById('label-url').innerText = 'Embed URL';
            } else {
                document.getElementById('label-url').innerText = 'URL';
            }

            urlGroup.style.display = 'block';
            fileGroup.style.display = 'none';
            contentUrl.required = true;
        } else if (contentType === 'pdf' || contentType === 'scorm') {
            urlGroup.style.display = 'none';
            fileGroup.style.display = 'block';
            contentUrl.required = false;
        } else {
            urlGroup.style.display = 'none';
            fileGroup.style.display = 'none';
            contentUrl.required = false;
        }
    }
</script>
@endpush