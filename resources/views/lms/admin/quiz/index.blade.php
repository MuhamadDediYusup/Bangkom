@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="d-flex align-content-center">
                            <div class="flex-grow-1 bd-highlight">
                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <select name="course_id" id="course_id" class="form-control">
                                                <option value="">Pilih Kursus</option>
                                                @foreach ($courses as $course)
                                                <option value="{{ $course->slug }}" {{ request()->segment(4) ==
                                                    $course->slug || (request()->segment(4) == null &&
                                                    session('course_slug') == $course->slug) ? 'selected' : '' }}>
                                                    {{ $course->course_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('lms-create')
                            <a href="{{ route('lms.admin.quiz.create') }}" class="btn btn-primary mb-5"
                                id="add_quiz_btn">Tambah Kuis</a>
                            @endcan
                        </div>
                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>Nama Kursus</th>
                                    <th>Nama Pelajaran</th>
                                    <th>Nama Kuis</th>
                                    <th>Deskripsi</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="20%">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        var slug = "{{ request()->segment(4) ?: session('course_slug') }}";

        var ajaxConfig = {
            url: '{{ route('lms.admin.quiz.getallquiz') }}' + '/' + slug,
        };

        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxConfig,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'courses.course_name', name: 'courses.course_name' },
                { data: 'lessons.lesson_name', name: 'lessons.lesson_name' },
                { data: 'quiz_name', name: 'quiz_name' },
                { data: 'description', name: 'description' },
                @can('lms-edit', 'lms-delete')
                { data: 'action', name: 'action', orderable: false, searchable: false }
                @endcan
            ],
            responsive: true
        });
    });
</script>

<script>
    @can('lms-list')
    document.getElementById('course_id').addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            window.location.href = "{{ route('lms.admin.quiz.index', '') }}/" + slug;
        }
    });
    @endcan

    @can('lms-create')
    // Update 'Tambah quiz' button link with slug
    var courseSelect = document.getElementById('course_id');
    var addquizBtn = document.getElementById('add_quiz_btn');
    courseSelect.addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            addquizBtn.href = "{{ route('lms.admin.quiz.create') }}" + "/" + slug;
        } else {
            addquizBtn.href = "{{ route('lms.admin.quiz.create') }}";
        }
    });

    // Set initial 'Tambah quiz' button link based on selected course
    window.onload = function() {
        var initialSlug = courseSelect.value;
        if (initialSlug) {
            addquizBtn.href = "{{ route('lms.admin.quiz.create') }}" + "/" + initialSlug;
        } else {
            addquizBtn.href = "{{ route('lms.admin.quiz.create') }}";
        }
    };
    @endcan
</script>
@endpush

@push('js')
@include('lms.partials.alert')
@can('lms-delete')
@include('lms.partials.modal_delete')
<script>
    $(document).on('click', '.delete', function() {
        var courseId = $(this).data('id');
        var courseName = $(this).data('name');
        $('#text-item-delete').text(courseName);
        $('#sub-item-delete-text').text("Anda Akan Menghapus Kuis, Pertanyaan, dan Jawaban yang Terkait dengan Kuis Ini.");
        $('#form-delete').attr('action', '{{ route("lms.admin.quiz.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush