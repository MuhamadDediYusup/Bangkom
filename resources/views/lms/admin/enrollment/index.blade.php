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
                            <a href="{{ route('lms.admin.enrollment.create', [request()->segment(4)]) }}"
                                class="btn btn-primary mb-5" id="admin_lesson_btn">Tambah Enrollment</a>
                            @endcan
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>Nama Kursus</th>
                                    <th>Tgl. Enrollment</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="8%">Aksi</th>
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

        console.log(slug);

        var ajaxConfig = {
            url: '{{ route('lms.admin.enrollment.getallcourses') }}' + '/' + slug,
        };

        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxConfig,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_id', name: 'user_id' },
                { data: 'employee.nama_lengkap', name: 'employee.nama_lengkap' },
                { data: 'course.course_name', name: 'course.course_name' },
                {
                    data: 'enrolled_at',
                    name: 'enrolled_at',
                    render: function(data) {
                        var date = new Date(data);
                        var day = date.getDate();
                        var month = date.getMonth() + 1;
                        var year = date.getFullYear();
                        return day + '-' + month + '-' + year;
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

@can('lms-list', 'lms-create')
<script>
    document.getElementById('course_id').addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            window.location.href = "{{ route('lms.admin.enrollment.index', '') }}/" + slug;
        }
    });

    // Update 'Tambah Module' button link with slug
    var courseSelect = document.getElementById('course_id');
    var addModuleBtn = document.getElementById('add_module_btn');
    courseSelect.addEventListener('change', function() {
        var slug = this.value;
        if(slug) {
            addModuleBtn.href = "{{ route('lms.admin.enrollment.create') }}" + "/" + slug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.enrollment.create') }}";
        }
    });

    // Set initial 'Tambah Module' button link based on selected course
    window.onload = function() {
        var initialSlug = courseSelect.value;
        if (initialSlug) {
            addModuleBtn.href = "{{ route('lms.admin.enrollment.create') }}" + "/" + initialSlug;
        } else {
            addModuleBtn.href = "{{ route('lms.admin.enrollment.create') }}";
        }
    };
</script>
@endcan
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
        $('#form-delete').attr('action', '{{ route("lms.admin.enrollment.destroy", ":id") }}'.replace(':id', entrollmentId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush