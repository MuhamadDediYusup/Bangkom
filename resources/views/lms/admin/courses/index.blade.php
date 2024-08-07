@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        @can('lms-create')
                        <div class="text-right mb-4">
                            <a href="{{ route('lms.admin.course.create') }}" class="btn btn-primary">Tambah
                                Kursus</a>
                        </div>
                        @endcan
                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th width="10%">flyer</th>
                                    <th>Nama Kursus</th>
                                    <th>Slug</th>
                                    <th>Aktif</th>
                                    @can('lms-list', 'lms-edit', 'lms-delete')
                                    <th width="15%" class="text-center">Aksi</th>
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
        var table = $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('lms.admin.courses.getallcourses') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'course_name', name: 'course_name' },
                { data: 'slug', name: 'slug' },
                { data: 'is_active', name: 'is_active', className: 'text-center', render: function(data) {
                    return data == 1 ? '<span class="badge badge-outline-success">Aktif</span>' : '<span class="badge badge-outline-danger">Tidak Aktif</span>';
                }},
                @can('lms-list', 'lms-edit', 'lms-delete')
                { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false },
                @endcan
            ],
            responsive: true
        });
    });
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
        $('#sub-item-delete-text').text("Tindakan ini akan menghapus kursus, modul, pelajaran, dan tugas yang terkait dengan kursus ini.");
        $('#form-delete').attr('action', '{{ route("lms.admin.course.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush