@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.lesson.update', [$lesson->lesson_id]) }}" method="POST"
            enctype="multipart/form-data">
            @method('PUT')
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
                                    required value="{{ $lesson->lesson_chapter }}">
                                @error('lesson_chapter')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Nama Pelajaran</label>
                                <input type="text" id="lesson_name" name="lesson_name" class="form-control" required
                                    value="{{ $lesson->lesson_name }}">
                                @error('lesson_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Jenis Pelajaran</label>
                                <select id="content_type" name="content_type" class="form-control">
                                    <option value="video" {{ $lesson->content_type == 'video' ? 'selected' : '' }}>Video
                                    </option>
                                    <option value="pdf" {{ $lesson->content_type == 'pdf' ? 'selected' : '' }}>Modul PDF
                                    </option>
                                    <option value="text" {{ $lesson->content_type == 'text' ? 'selected' : '' }}>Konten
                                        Teks</option>
                                    <option value="quiz" {{ $lesson->content_type == 'quiz' ? 'selected' : '' }}>Kuis
                                    </option>
                                    <option value="url" {{ $lesson->content_type == 'url' ? 'selected' : '' }}>Url
                                        (flipbook, etc)</option>
                                    <option value="powerpoint" {{ $lesson->content_type == 'powerpoint' ? 'selected' :
                                        '' }}>Slide Powerpoint</option>
                                    <option value="scorm" {{ $lesson->content_type == 'scorm' ? 'selected' : '' }}>SCORM
                                        Package</option>
                                </select>
                            </div>

                            <div class="form-group" id="url-group">
                                <label>URL Video</label>
                                <input type="text" id="content_url" name="content_url" class="form-control"
                                    value="{{ $lesson->content_url }}">
                                @error('content_url')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" id="file-group" style="display:none;">
                                <label id="label-file-group">Upload File</label>
                                <input type="file" id="content_file" name="content_file" class="form-control"
                                    accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .zip">
                                @if (!empty($lesson->content_url) && ($lesson->content_type == 'pdf' ||
                                $lesson->content_type == 'powerpoint'))
                                <small><a href="{{ asset('files/lessons/' . $lesson->content_url) }}"
                                        target="_blank">Lihat File</a></small>
                                @elseif($lesson->content_type == 'scorm')
                                <small><a
                                        href="{{ asset('files/scorm/' . $dataScorm->uuid . '/' . $dataScorm->entry_url) }}"
                                        target="_blank">Lihat SCORM</a></small>
                                @endif
                                @error('content_file')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label>Konten Pelajaran</label>
                                <textarea name="content" id="content"
                                    class="form-control summernote">{{ $lesson->content }}</textarea>
                                @error('content')
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

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        handleContentTypeChange();
        document.getElementById('content_type').addEventListener('change', handleContentTypeChange);
    });

    function handleContentTypeChange() {
        const contentType = document.getElementById('content_type').value;
        const urlGroup = document.getElementById('url-group');
        const fileGroup = document.getElementById('file-group');
        const contentUrl = document.getElementById('content_url');
        const fileUpload = document.getElementById('content_file');

        // Hide all groups initially
        urlGroup.style.display = 'none';
        fileGroup.style.display = 'none';

        // Handle based on content type
        if (contentType === 'video' || contentType === 'powerpoint') {
            urlGroup.style.display = 'block';
            contentUrl.required = true;
            fileUpload.required = false;
        } else if (contentType === 'pdf') {
            fileGroup.style.display = 'block';
            contentUrl.required = false;
            fileUpload.required = true;
        } else if (contentType === 'scorm') {
            fileGroup.style.display = 'block';
            document.getElementById('label-file-group').textContent = 'Upload SCORM Package';
        } else {
            contentUrl.required = false;
            fileUpload.required = false;
        }
    }
</script>
@endpush