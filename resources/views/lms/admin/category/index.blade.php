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
                            <a href="{{ route('lms.admin.category.create') }}" class="btn btn-primary">Tambah
                                Kategori</a>
                        </div>
                        @endcan
                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>Kategori</th>
                                    <th>Color Tag</th>
                                    <th>Slug</th>
                                    @can('lms-edit', 'lms-delete')
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
            ajax: '{{ route('lms.admin.categories.getallcategories') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'category_name', name: 'category_name' },
                { data: 'category_with_badge', name: 'category_with_badge' },
                { data: 'slug', name: 'slug' },
                @can('lms-edit', 'lms-delete')
                { data: 'action', name: 'action', className: 'text-center', orderable: false, searchable: false },
                @endcan
            ],
            responsive: true
        });
    });
</script>
</script>
@endpush

@push('js')
@include('lms.partials.alert')
@include('lms.partials.modal_delete')
<script>
    $(document).on('click', '.delete', function() {
        var courseId = $(this).data('id');
        var courseName = $(this).data('name');

        $('#text-item-delete').text(courseName);
        $('#form-delete').attr('action', '{{ route("lms.admin.category.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endpush