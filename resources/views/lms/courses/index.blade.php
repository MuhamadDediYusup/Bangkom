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
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{ url('course/flyer/' . $course->img_flyer) }}">
                        </div>
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
                            <h2><a href="{{ route('lms.course.show', $course->slug) }}">{{ $course->course_name }}</a>
                            </h2>
                        </div>
                        <p title="{{ $course->description }}" data-toggle="tooltip" data-placement="right"
                            data-original-title="{{ $course->description }}">
                            {{ \Illuminate\Support\Str::limit($course->description, 150) }}
                        </p>
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