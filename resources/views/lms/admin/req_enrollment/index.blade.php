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
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>Nama Kursus</th>
                                    <th>Tgl. Request</th>
                                    <th class="text-center">Status</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="15%" class="text-center">Approvement</th>
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
        var currentUrl = window.location.href.split('/');
        var slug = "{{ request()->segment(4) }}";

        var ajaxConfig = {
            url: '{{ route('lms.admin.request.getallrequest') }}' + '/' + slug,
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
                    data: 'requested_at',
                    name: 'requested_at',
                    render: function(data) {
                        var date = new Date(data);
                        var day = date.getDate();
                        var month = date.getMonth() + 1;
                        var year = date.getFullYear();
                        return day + '-' + month + '-' + year;
                    }
                },
                { data: 'status', name: 'status', className: 'text-center', render: function(data) {
                    if (data == 0) {
                        return '<span class="badge badge-warning">Pending</span>';
                    } else if (data == 1) {
                        return '<span class="badge badge-success">Approved</span>';
                    } else {
                        return '<span class="badge badge-danger">Rejected</span>';
                    }
                }},
                @can('lms-edit', 'lms-delete')
                { data: 'approval_action', name: 'approval_action', className:'text-center', orderable: false, searchable: false },
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
        var slug = this.value;
        if(slug) {
            window.location.href = "{{ route('lms.admin.request-access.index', '') }}/" + slug;
        }
    });
</script>

<script>
    $(document).on('click', '.delete', function() {
        var requestId = $(this).data('id');
        var requestNIP = $(this).data('name');

        $('#text-item-delete').text(requestNIP);
        $('#text-desc').text('Apakah anda yakin akan menghapus ');
        $('#text-delete').text('Hapus');
        $('#form-delete').attr('action', '{{ route("lms.admin.request-access.destroy", ":id") }}'.replace(':id', requestId));
        $('#deletemodal').modal('show');
    });

    $(document).on('click', '.approve', function() {
        var requestId = $(this).data('id');
        var requestNIP = $(this).data('name');

        $('#text-item-approve').text(requestNIP);
        $('#text-desc').text('Apakah anda yakin akan menyetujui ');
        $('#text-approve').text('Setujui');
        $('#form-approve').attr('action', '{{ route("lms.admin.request-access.approve", ":id") }}'.replace(':id', requestId));
        $('#approve_modal').modal('show');
    });

    $(document).on('click', '.reject', function() {
        var requestId = $(this).data('id');
        var requestNIP = $(this).data('name');

        $('#text-item-approve').text(requestNIP);
        $('#text-desc').text('Apakah anda yakin akan menolak ');
        $('#text-approve').text('Tolak');
        $('#form-approve').attr('action', '{{ route("lms.admin.request-access.reject", ":id") }}'.replace(':id', requestId));
        $('#approve_modal').modal('show');
    });

</script>
@include('lms.partials.alert')
@include('lms.partials.modal_delete')
@include('lms.partials.modal_approve')
@endpush