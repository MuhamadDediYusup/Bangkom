@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.token.update', [$token->token_id]) }}" method="POST">
            @method('PUT')
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
                            <div class="form-group">
                                <label>Kode Token</label>
                                <input type="text" id="token" name="token" class="form-control" required
                                    value="{{ old('token') ?? $token->token }}" required
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('token')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_active">Aktif?</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ old('is_active')=='1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ old('is_active')=='0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('is_active')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Token</button>

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