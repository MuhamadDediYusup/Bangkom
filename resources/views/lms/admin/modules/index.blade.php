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
                            <a href="{{ route('lms.admin.module.create') }}" class="btn btn-primary mb-5"
                                id="add_module_btn">Tambah Module</a>
                            @endcan
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="5%">Bab Modul</th>
                                    <th>Nama Kursus</th>
                                    <th>Nama Module</th>
                                    @can('lms-list', 'lms-edit', 'lms-delete')
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

        if (slug) {
            $('#courses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('lms.admin.modules.getallmodules') }}',
                    data: { slug: slug }
                },
                columns: [
                    { data: 'module_chapter', name: 'module_chapter', className: 'text-center' },
                    { data: 'course.course_name', name: 'course.course_name' },
                    { data: 'module_name', name: 'module_name' },
                    @can('lms-list', 'lms-edit', 'lms-delete')
                    { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false }
                    @endcan
                ],
                responsive: true
            });
        } else {
        var table = $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('lms.admin.modules.getallmodules') }}',
            columns: [
                { data: 'module_chapter', name: 'module_chapter' },
                { data: 'course.course_name', name: 'course.course_name' },
                { data: 'module_name', name: 'module_name' },
                @can('lms-list', 'lms-edit', 'lms-delete')
                { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false },
                @endcan
            ],
            responsive: true
        });
    }
    });
</script>

<script>
    @can('lms-list')
    document.getElementById('course_id').addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            window.location.href = "{{ route('lms.admin.module.index', '') }}/" + slug;
        }
    });
    @endcan

    @can('lms-create')
    // Update 'Tambah Module' button link with slug
    var courseSelect = document.getElementById('course_id');
    var addModuleBtn = document.getElementById('add_module_btn');
    courseSelect.addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            addModuleBtn.href = "{{ route('lms.admin.module.create') }}" + "/" + slug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.module.create') }}";
        }
    });

    // Set initial 'Tambah Module' button link based on selected course
    window.onload = function() {
        var initialSlug = courseSelect.value;
        if (initialSlug) {
            addModuleBtn.href = "{{ route('lms.admin.module.create') }}" + "/" + initialSlug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.module.create') }}";
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
        $('#form-delete').attr('action', '{{ route("lms.admin.module.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush