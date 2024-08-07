@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.course.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Info Kursus</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Gambar Kursus</label>
                                <input type="file" name="img_flyer" class="dropify" data-max-file-size="2M" required>
                                @error('img_flyer')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Kursus</label>
                                <input type="text" id="course_name" name="course_name" class="form-control" required
                                    value="{{ old('course_name') }}">
                                <input type="hidden" id="slug" name="slug" class="form-control" required
                                    value="{{ old('slug') }}">
                                <span class="text-muted" id="slug-show"></span>
                                @error('course_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Singkat</label>
                                <input type="text" name="description" class="form-control" required
                                    value="{{ old('description') }}">
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Kategori Kursus</label>
                                <select name="category_id" class="form-control select2" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id')==$category->
                                        category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Total Jam Pelajaran</label>
                                <input type="text" name="total_hours" class="form-control" required
                                    value="{{ old('total_hours') }}">
                                @error('total_hours')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Tanggal Mulai - Selesai</label>
                                <input type="text" name="date" class="form-control datepicker" required
                                    value="{{ old('date') }}">
                                @error('date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>NIP Instruktur</label>
                                <input type="text" name="instructor_id" class="form-control"
                                    value="{{ old('instructor_id') }}">
                                @error('instructor_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Instruktur</label>
                                <input type="text" name="instructor_name" class="form-control"
                                    value="{{ old('instructor_name') }}">
                                @error('instructor_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Publik?</label>
                                <select name="requires_token" class="form-control" required>
                                    <option value="1" {{ old('requires_token')=='1' ? 'selected' : '' }}>Iya</option>
                                    <option value="0" {{ old('requires_token')=='0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('requires_token')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Aktif?</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1" {{ old('is_active')=='1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active')=='0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Kursus</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="competence">Detail Kursus</label>
                                <textarea name="detail_course" class="summernote"
                                    required>{{ old('detail_course') }}</textarea>
                                @error('detail_course')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="competence">Target Peserta</label>
                                <textarea name="target_participants" class="summernote-simple"
                                    required>{{ old('target_participants') }}</textarea>
                                @error('target_participants')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="competence">Tujuan Kelas</label>
                                <textarea name="objectives" class="summernote-simple"
                                    required>{{ old('objectives') }}</textarea>
                                @error('objectives')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="competence">Kompetensi</label>
                                <textarea name="competence" id="competence" class="summernote-simple"
                                    required>{{ old('competence') }}</textarea>
                                @error('competence')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button class="btn btn-primary" type="submit">Simpan Kursus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });

    document.getElementById('course_name').addEventListener('input', function() {
        var courseName = this.value;
        var slug = courseName.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Hapus karakter yang tidak diinginkan
            .trim() // Hapus spasi di awal dan akhir
            .replace(/\s+/g, '-') // Ganti spasi dengan tanda hubung
            .replace(/-+/g, '-'); // Ganti tanda hubung berlebih dengan satu tanda hubung
        document.getElementById('slug').value = slug;
        document.getElementById('slug-show').innerText = slug;
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endpush
