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
                                            <select name="course_id" id="course_id" class="form-control mr-2">
                                                <option value="" selected>Pilih Kursus</option>
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
                            <a href="{{ route('lms.admin.token.create', [request()->segment(4)]) }}"
                                class="btn btn-primary mb-5" id="admin_lesson_btn">Tambah Token</a>
                            @endcan
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>Token</th>
                                    <th>Nama Kursus</th>
                                    <th>Aktif?</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="15%">Aksi</th>
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
        var slug = "{{ request()->segment(4) ?? session('course_slug') }}";

        var ajaxConfig = {
            url: '{{ route('lms.admin.token.getalltoken') }}' + '/' + slug,
        };

        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxConfig,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'token', name: 'token' },
                { data: 'course.course_name', name: 'course.course_name' },
                {
                    data: 'is_active',
                    name: 'is_aktive',
                    render: function(data) {
                        return data == 1 ? 'Aktif' : 'Tidak';
                    }
                },
                @can('lms-edit', 'lms-delete')
                { data: 'action', name: 'action', orderable: false, searchable: false }
                @endcan
            ],
            responsive: true
        });
        }
    );
</script>

<script>
    document.getElementById('course_id').addEventListener('change', function() {
        var slug = this.value || "{{ session('course_slug') }}";
        if(slug) {
            window.location.href = "{{ route('lms.admin.token.index', '') }}/" + slug;
        }
    });

    @can('lms-create')
    // Update 'Tambah Module' button link with slug
    var courseSelect = document.getElementById('course_id');
    var addModuleBtn = document.getElementById('add_module_btn');
    courseSelect.addEventListener('change', function() {
        var slug = this.value || "{{ session('course_slug') }}";
        if(slug) {
            addModuleBtn.href = "{{ route('lms.admin.token.create') }}" + "/" + slug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.token.create') }}";
        }
    });

    // Set initial 'Tambah Module' button link based on selected course or session value
    window.onload = function() {
        var initialSlug = courseSelect.value || "{{ session('course_slug') }}";
        if (initialSlug) {
            addModuleBtn.href = "{{ route('lms.admin.token.create') }}" + "/" + initialSlug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.token.create') }}";
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
        var entrollmentId = $(this).data('id');
        var enrollmentNip = $(this).data('name');

        $('#text-item-delete').text(enrollmentNip);
        $('#form-delete').attr('action', '{{ route("lms.admin.token.destroy", ":id") }}'.replace(':id', entrollmentId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush