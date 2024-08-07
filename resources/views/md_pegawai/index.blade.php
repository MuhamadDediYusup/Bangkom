@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-8">
                            <h4>Daftar ASN Pemerintah Kabupaten Sleman</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2">
                                <thead>
                                    <tr>
                                        <th>
                                            NIP
                                        </th>
                                        <th>Nama Lengkap</th>
                                        <th>Perangkat Daerah</th>
                                        <th>ID Perangkat Daerah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pegawai as $item)
                                    <tr>
                                        <td> {{ $item->nip }}</td>
                                        <td>
                                            {{ $item->nama_lengkap }}
                                        </td>
                                        <td>
                                            {{ $item->perangkat_daerah }}
                                        </td>
                                        <td>
                                            {{ $item->id_perangkat_daerah }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>Data diperbaharui pada : <b class="badge badge-info">
                                        {{ $pegawai->first()->updated_at->format('d-m-Y | H:i:s') }} WIB
                                    </b>
                                </p>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                @can('master-data-update')
                                <form action="{{ route('md_pegawai.update') }}" id="update_pegawai" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary btn-lg btn-block btn-icon-split update">
                                        <span>Update Data Pegawai</span>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
                $('.update').click(function (e) {
                        e.preventDefault();
                        swal({
                                title: "Apakah Anda Akan Mengupdate Data Pegawai ?",
                                text: "Anda akan menghapus dan mengupdate data pegawai",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                        }).then((willDelete) => {
                                if (willDelete) {
                                        $('#update_pegawai').submit();
                                } else {
                                        swal("Data Tidak Diupdate");
                                }
                        });
                });
        });
</script>

@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Sukses', "{{ session('success') }}", 'success');
</script>
@endif

@endpush

@endsection
