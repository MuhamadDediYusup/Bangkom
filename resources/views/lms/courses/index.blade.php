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
                        <option value="{{ $category->slug }}" {{ request()->segment(4) == $category->slug? 'selected' :
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
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 d-flex align-items-stretch">
                <div class="card course-card">
                    <div class="card-header p-0 position-relative">
                        <img src="{{ url('course/flyer/' . $course->img_flyer) }}" class="card-img-top"
                            alt="{{ $course->course_name }}">
                        <div class="badge-overlay">
                            <a href="{{ route('lms.course.category', $course->category->slug) }}">
                                <span class="badge {{ $course->category->color_tag }}">
                                    {{ $course->category->category_name }}
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->course_name }}</h5>
                        <p class="card-text" title="{{ $course->description }}" data-toggle="tooltip"
                            data-placement="right" data-original-title="{{ $course->description }}">
                            {{ \Illuminate\Support\Str::limit($course->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-score">4.0/5.0</span>
                            </div>
                            <div class="course-meta">
                                <span><i class="fa fa-clock"></i> 12h 56m</span>
                                <span><i class="fa fa-book-open"></i> 15 lectures</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('lms.course.show', $course->slug) }}"
                            class="btn btn-primary btn-block">Selengkapnya <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
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
    .course-card {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .course-card:hover {
        transform: translateY(-5px);
    }

    .card-header img {
        width: 100%;
        height: auto;
    }

    .badge-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 5px 10px;
        color: #fff;
        border-radius: 5px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .rating {
        display: flex;
        align-items: center;
    }

    .rating .fa-star {
        color: #ffdd57;
    }

    .rating .checked {
        color: #ffdd57;
    }

    .rating-score {
        font-size: 0.875rem;
        margin-left: 5px;
    }

    .course-meta span {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .course-meta i {
        margin-right: 3px;
    }

    .card-footer {
        background-color: #fff;
        border-top: none;
    }

    .card-footer .btn {
        font-size: 0.875rem;
        padding: 10px 20px;
    }

    .card-footer .btn i {
        margin-left: 5px;
    }

    /* Ensures
</style>
@endpush