@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk mengubah About Abangkomandan</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>About Abangkomandan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pendukung.about.update') }}" method="post">
                    @csrf
                    <div class="form-group row mb-4">
                        <div class="col-sm-12 col-md-12">
                            <textarea class="summernote" name="text_about">{{ $about->text_about }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <div class="col-sm-12 col-md-12">
                            <button class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pendukung.about') }}" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection

@push('js')
<script>
    $('.summernote').summernote({
    toolbar: [
        ['style',['style']],
        ['font style', ['bold', 'italic', 'underline', 'clear', 'fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['link',['link']]
    ]
    });
</script>
@endpush