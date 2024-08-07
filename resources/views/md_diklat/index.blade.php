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
                            <h4>Master Data Diklat</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">Jenis Diklat</th>
                                        <th class="text-center">Sub Jenis Diklat</th>
                                        <th class="text-center">Rumpun Diklat</th>
                                        <th class="text-center">ID Diklat</th>
                                        <th class="text-center">ID SIASN</th>
                                        <th class="text-center">Sertifikat SIASN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diklat as $item)
                                    <tr>
                                        <td>{{ $item->jenis_diklat }}</td>
                                        <td>{{ $item->sub_jenis_diklat }}</td>
                                        <td>{{ $item->rumpun_diklat }}</td>
                                        <td class="text-center">{{ $item->id_diklat }}</td>
                                        <td class="text-center">{{ $item->id_siasn }}</td>
                                        <td class="text-center">{{ $item->sertifikat_siasn }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <p>Data diperbaharui pada : <b class="badge badge-info">
                                        {{ $diklat->first()->updated_at }} WIB
                                    </b>
                                </p>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                @can('master-data-update')
                                <form action="{{ route('md_diklat.update') }}" id="update_diklat" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary btn-lg btn-block btn-icon-split update">
                                        <span>Update Data Diklat</span>
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
@endsection


@push('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
                $('.update').click(function (e) {
                        e.preventDefault();
                        swal({
                                title: "Apakah Anda Akan Mengupdate Data diklat ?",
                                text: "Anda akan menghapus dan mengupdate data diklat",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                        }).then((willDelete) => {
                                if (willDelete) {
                                        $('#update_diklat').submit();
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