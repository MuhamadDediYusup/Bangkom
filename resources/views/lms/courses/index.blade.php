@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert')

<!-- Main Content -->
<section class="section">
    <div class="section-body">

        @if ($courses->count() > 0)
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">Daftar Kursus</h2>
                <p class="section-lead">
                    Berikut adalah daftar kursus yang tersedia di platform ini. Silahkan pilih kursus yang ingin Anda
                    ikuti.
                </p>
            </div>
            <div>
                <form id="categoryForm" method="GET">
                    <select id="category" class="form-control" onchange="updateFormActionAndSubmit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" {{ request()->segment(4) == $category->slug? 'elected' :
                            '' }}>
                            {{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach ($courses as $course)
            <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4">
                <article class="article">
                    <div class="article-header">
                        <div class="article-image"
                            style="background-image: url('{{ url('course/flyer/' . $course->img_flyer) }}');"></div>
                        <div class="article-badge">
                            <a href="{{ route('lms.course.category', $course->category->slug) }}">
                                <div class="article-badge-item {{ $course->category->color_tag }}">
                                    <i class="fa-solid fa-layer-group"></i>
                                    {{ $course->category->category_name }}
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ route('lms.course.show', $course->slug) }}" data-toggle="tooltip"
                                    data-placement="right" data-original-title="{{ $course->description }}">{{
                                    $course->course_name }}</a>
                            </h2>
                        </div>
                        {{-- <p title="{{ $course->description }}" data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ $course->description }}">
                            {{ \Illuminate\Support\Str::limit($course->description, 150) }}
                        </p> --}}

                        @if ($course->enrollments != null)
                        <p>SUDAH</p>
                        @else
                        <p>BELUM</p>
                        @endif


                        <div>
                            <strong></strong>
                        </div>

                        <div class="article-cta">
                            <a href="{{ route('lms.course.show', $course->slug) }}">Selengkapnya <i
                                    class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
            @else
            <div class="alert alert-info">
                Tidak ada kursus yang tersedia.
            </div>
            @endif
        </div>
</section>
@endsection

@push('js')
<script>
    function updateFormActionAndSubmit() {
        var select = document.getElementById('category');
        var selectedSlug = select.options[select.selectedIndex].value;

        // Update URL
        if (selectedSlug) {
            window.location.href = "{{ url('lms/courses/category') }}/" + selectedSlug;
        } else {
            window.location.href = "{{ route('lms.course.index') }}";
        }
    }
</script>
@endpush

@push('css')
<style>
    .article {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .article:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .article-header {
        position: relative;
        height: 200px;
    }

    .article-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
    }

    .article-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }

    .article-badge-item {
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .article-badge-item i {
        margin-right: 5px;
    }

    .article-details {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .article-title h2 {
        font-size: 1.5em;
        margin: 0 0 10px;
    }

    .article-title h2 a {
        text-decoration: none;
        color: #333;
    }

    .article-title h2 a:hover {
        color: #007bff;
    }

    .article-cta a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
    }

    .article-cta a:hover {
        text-decoration: underline;
    }

    .article-cta a i {
        margin-left: 5px;
    }

    .tooltip-inner {
        max-width: 200px;
    }
</style>
@endpush