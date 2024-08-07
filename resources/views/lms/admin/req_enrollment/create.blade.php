@extends('lms.layout.main')

@section('content')
@include('partials.section_header')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.enrollment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Kursus</label>
                                <input type="text" id="course_name" name="course_name" class="form-control" required
                                    value="{{ $course->course_name }}" readonly>
                                <input type="hidden" id="course_id" name="course_id" class="form-control" required
                                    value="{{ $course->course_id }}">
                                <input type="hidden" id="slug" name="slug" class="form-control" required
                                    value="{{ $course->slug }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Daftar Pegawai</label>

                                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                                            <thead>
                                                <tr>
                                                    <th width="2px">#</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Daftar Pegawai yang Ditambahkan</label>
                                        <ul id="selected-employees" class="list-group">
                                            <!-- List of selected employees will be appended here -->
                                        </ul>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Enrollmen</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('lms.admin.enrollment.geteployeedata') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nip', name: 'nip' },
                { data: 'nama_lengkap', name: 'nama_lengkap' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            responsive: true
        });
        $('#courses-table').on('click', '.add', function() {
            var nip = $(this).data('id');
            var nama = $(this).data('name');

            // Check if the employee is already in the list
            if ($('#selected-employees input[value="' + nip + '"]').length === 0) {
                var listItem = '<li class="list-group-item">' + nama +
                    '<input type="hidden" name="employees[]" value="' + nip + '"></li>';

                $('#selected-employees').append(listItem);
            } else {
                alert('Pegawai ini sudah ada dalam daftar!');
            }
        });
        }
    );
</script>
@endpush