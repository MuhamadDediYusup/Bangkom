@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

{{-- @dd($kompetensi) --}}
<div class="section-body">
    <section>
        <form action="{{ route('kompetensiasn.index') }}" method="get">
            @include('partials.form_search')
        </form>

        @if (isset($identitas))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="text-dark">Data Simpeg - Identitas ASN</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->nama_lengkap }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">NIP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form" value="{{ $identitas->nip }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Tempat, Tgl. Lahir</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->lahir_tempat }}, {{ $identitas->lahir_tanggal }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Pangkat/Gol.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->pangkat }}, {{ $identitas->golru }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Pendidikan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->pendidikan }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Sekolah</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->sekolah }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Jenis Jabatan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->jenis_jabatan }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Jenjang Jabatan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="form"
                                            value="{{ $identitas->jenjang_jabatan }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Jabatan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" style="height: 100px"
                                            disabled>{{ $identitas->jabatan }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="form" class="col-sm-3 col-form-label">Perangkat Daerah</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" style="height: 100px"
                                            disabled>{{ $identitas->sub_satuan_organisasi }} - {{ $identitas->satuan_organisasi }} - {{ $identitas->perangkat_daerah }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($jamPelajaran))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="text-dark">Pengembangan Kompetensi (JP)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Bang Kom</th>
                                                <th class="text-center">JP Manajerial</th>
                                                <th class="text-center">JP Teknis</th>
                                                <th class="text-center">JP Fungsional</th>
                                                <th class="text-center">JP Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $no = 1;
                                            @endphp
                                            @foreach ($jamPelajaran as $item)
                                            <tr>
                                                <td class="text-center">Tahun {{ Carbon\Carbon::now()->year }}
                                                </td>
                                                <td class="text-center">{{ $item->jp_manajerial }}</td>
                                                <td class="text-center">{{ $item->jp_teknis }}</td>
                                                <td class="text-center">{{ $item->jp_fungsional }}</td>
                                                <td class="text-center fw-bold">{{ $item->jp_total }}</td>
                                                @php
                                                $no++;
                                                @endphp
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($kompetensi))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="text-dark">Data Simpeg - Kompetensi ASN</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-3" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Jenis Diklat</th>
                                        <th class="text-center">Sub Jenis Diklat</th>
                                        <th class="text-center">Nama Diklat</th>
                                        <th class="text-center">JP</th>
                                        <th class="text-center">Nomor STTPP</th>
                                        <th class="text-center">Tanggal STTPP</th>
                                        @can('kompetensi-asn-edit', 'kompetensi-asn-delete')
                                        <th class="text-center">Admin</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($kompetensi as $item)
                                    <tr>
                                        <td class="text-center">{{ $no }}</td>
                                        <td>{{ $item->jenis_diklat }}</td>
                                        <td style="max-width:150px;">{{ $item->sub_jenis_diklat }}
                                        </td>
                                        <td>{{ $item->nama_diklat }}
                                            @if (!empty($item->file))
                                            {{-- <sup data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="File STTPP Simpeg"><a
                                                    href="https://simpeg.slemankab.go.id{{ $item->file }}"
                                                    target="_blank" class="text-dark"><span class="text-danger"><b
                                                            class="">
                                                            PDF</b></span></a></sup> --}}
                                            <sup data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="File STTPP Simpeg"><a href="javascript:void(0)"
                                                    data-sttpp-simpeg="https://simpeg.slemankab.go.id{{ $item->file }}"
                                                    id="pdfSimpeg" class="text-dark"><span class="text-danger"><b
                                                            class="">
                                                            PDF</b></span></a></sup>
                                            @endif
                                            @if (!empty($item->id_siasn))
                                            <sup><b class="text-primary" data-toggle="tooltip" data-placement="top"
                                                    title=""
                                                    data-original-title="Data terintegrasi dengan SIASN">SIASN</b></sup>
                                            @endif
                                        </td>
                                        <td class=" text-center">{{ $item->lama_pendidikan }}</td>
                                        <td style="max-width: 50px">{{ $item->nomor_sttpp }}</td>
                                        <td>{{ $item->tgl_sttpp }}</td>
                                        @can('kompetensi-asn-edit', 'kompetensi-asn-delete')
                                        <td class="text-center">
                                            @can('kompetensi-asn-edit')
                                            @if ($item->jenis_diklat != '')
                                            <a href="{{ route('kompetensiasn.edit', [$item->jenis_diklat, $item->nip, $item->id_pegawai_pendidikan]) }}"
                                                class="icon-edit" data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Edit Kompetensi ASN">
                                                <i class="fa-regular fa-pen-to-square"></i></a>
                                            @endif
                                            @endcan
                                            @can('kompetensi-asn-delete')
                                            <a href="javascript:void(0)" class="icon-delete-asn text-danger"
                                                data-nip-pegawai="{{ $identitas->nip }}"
                                                data-jenis-diklat="{{ $item->jenis_diklat }}"
                                                data-idpegawai-pendidikan="{{ $item->id_pegawai_pendidikan }}"
                                                data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Hapus Kompetensi ASN">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                            @endcan
                                        </td>
                                        @endcan
                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($usulan))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="text-dark">Data Usulan Bang Kom</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-3" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Jenis Diklat</th>
                                        <th>Sub Jenis Diklat</th>
                                        {{-- <th class="text-center">Rumpun Diklat</th> --}}
                                        <th>Nama Diklat</th>
                                        <th class="text-center">Tgl. Usulan</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($usulan as $item)
                                    <tr>
                                        <td class="text-center">{{ $no }}</td>
                                        <td>{{ $item->jenis_diklat }}</td>
                                        <td>{{ $item->sub_jenis_diklat }}</td>
                                        {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                        <td>{{ $item->nama_diklat }}</td>
                                        <td class="text-center">
                                            {{ Carbon\Carbon::parse($item->entry_time)->format('d-m-Y') }}</td>
                                        <td class="text-center">
                                            @if ($item->status_usulan == '0')
                                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Ditolak
                                            </span>
                                            @elseif ($item->status_usulan == '1')
                                            <span class="badge badge-success" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Disetujui</span>
                                            @elseif ($item->status_usulan == '9')
                                            <span class="badge badge-dark" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Dikirim</span>
                                            @else
                                            <span class="badge badge-warning" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Ditinjau</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($usulan))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="text-dark">Data Pengiriman Bang Kom</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-3" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Jenis Diklat</th>
                                        <th>Sub Jenis Diklat</th>
                                        {{-- <th class="text-center">Rumpun Diklat</th> --}}
                                        <th>Nama Diklat</th>
                                        <th class="text-center">SPT</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($pengiriman as $item)
                                    <tr>
                                        <td class="text-center">{{ $no }}</td>
                                        <td>{{ $item->jenis_diklat }}</td>
                                        <td>{{ $item->sub_jenis_diklat }}</td>
                                        {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                        <td>{{ $item->nama_diklat }}</td>
                                        <td class="text-center">
                                            {{ Carbon\Carbon::parse($item->tgl_surat)->format('d-m-Y') }}</td>
                                        <td class="text-center">
                                            @if ($item->status == '0')
                                            <span class="badge badge-success" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                Dikirim
                                            </span>
                                            @else
                                            <span class="badge badge-dark" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($laporan))
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header bg-light">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4 class="text-dark">Daftar Laporan Pengembangan Kompetensi</h4>
                        </div>
                        <div class="p-2 bd-highlight ">
                            @can('laporan-create')
                            <div class="dropdown d-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton3"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-circle-plus fa-beat"></i> &nbsp; Tambah Laporan
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start"
                                    style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{ route('laporan.redirect') }}">Fasilitasi
                                        BKPP</a>
                                    <a class="dropdown-item"
                                        href="{{ route('laporan.create', [$identitas->nip, '000']) }}">Fasilitasi
                                        Perangkat Daerah</a>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-3" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Jenis Diklat</th>
                                        <th>Sub Jenis Diklat</th>
                                        <th>Nama Diklat</th>
                                        <th class="text-center">JP</th>
                                        <th class="text-center">Nomor STTPP</th>
                                        <th class="text-center">Tanggal STTPP</th>
                                        <th class="text-center">Status</th>
                                        @can('laporan-approve')
                                        @can('laporan-edit')
                                        <th>Admin</th>
                                        @endcan
                                        @endcan
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                    $no = 1;
                                    @endphp
                                    @foreach ($laporan as $item)
                                    <tr>
                                        <td class="text-center">{{ $no }}</td>
                                        <td>{{ $item->jenis_diklat }}</td>
                                        <td>{{ $item->sub_jenis_diklat }}</td>
                                        {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                        <td>{{ $item->nama_diklat }}
                                            @if (!empty($item->file_sttpp))
                                            <sup data-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="File STTPP Lapoaran"><a href="javascript:void(0)"
                                                    data-link-laporan="{{ asset('Lamp_Sertifikat') }}/{{ $item->file_sttpp }}"
                                                    id="pdfLaporan" class="text-dark"><span class="text-danger"><b
                                                            class="">
                                                            PDF</b></span></a></sup>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->lama_pendidikan }}</td>
                                        <td>{{ $item->nomor_sttpp }}</td>
                                        <td class="text-center">
                                            {{ Carbon\Carbon::parse($item->tgl_sttpp)->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == '0')
                                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="top"
                                                title="" data-original-title="{{ $item->alasan }}">
                                                Ditolak
                                            </span>
                                            @elseif ($item->status == '1')
                                            <span class="badge badge-success" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Disetujui</span>
                                            @else
                                            <span class="badge badge-warning" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="{{ $item->edit_time != null ? Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') : Carbon\Carbon::parse($item->entry_time)->format('d-m-Y ; H:i') }} WIB">
                                                Ditinjau</span>
                                            @endif
                                        </td>
                                        @can('laporan-approve')
                                        <td class='text-center'>
                                            @can('laporan-edit')
                                            <a href="{{ url('/laporan/edit/') }}/{{ $identitas->nip }}/{{ $item->id_lapor }}/{{ $item->status ? $item->status : 'null' }}"
                                                data-toggle='tooltip' data-placement='top' title='Edit Laporan'> <i
                                                    class='fa-regular fa-pen-to-square'>
                                                </i></a>
                                            @endcan
                                            @can('laporan-delete')
                                            <a href='javascript:void(0)' class='icon-delete' id="delLaporan"
                                                data-id-delete='{{ $item->id_lapor }}' data-toggle='tooltip'
                                                data-placement='top' title='Hapus Laporan'> <i
                                                    class='fa-solid fa-trash-can text-danger'> </i></a>
                                            @endcan
                                        </td>
                                        @endcan
                                    </tr>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>

@if (session('error'))
@include('partials.error_alert')
@endif

@endsection

@push('js')

@push('css')
<style>
    .icon-exclamation {
        color: rgba(255, 15, 15, 0.5);
        font-size: 10rem;
    }
</style>
@endpush

{{-- modal for showing pdf file --}}
<div class="modal fade" id="modalShowPdfASN" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-3">
                    <iframe src="" frameborder="0" style="width: 100%; height: 80vh"></iframe>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            {{-- <div class="modal-footer">
            </div> --}}
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#pdfLaporan', function() {
            let url = $(this).attr('data-link-laporan');
            $('#modalShowPdfASN iframe').attr('src', url);
            $('#modalShowPdfASN').modal('show');
        });
</script>

<script>
    $(document).on('click', '#pdfSimpeg', function() {
            let url = $(this).attr('data-sttpp-simpeg');
            $('#modalShowPdfASN iframe').attr('src', url);
            $('#modalShowPdfASN').modal('show');
        });
</script>

<div class="modal fade" id="deletemodalASN" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-3">
                    <span class="icon-exclamation"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <h4 class="fw-bold">Apakah anda yakin akan menghapus <span id="text-item-delete-asn"></span> ini
                        di
                        Aplikasi Simpeg ?</h4>
                    <p class="mb-4">Anda tidak dapat mengembalikan data Simpeg ini!</p>
                    <form id="form-delete-asn" action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Hapus</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
            $('#table-2').on('click', '.icon-delete-asn', function() {
                $('#deletemodalASN').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });

                var dataNIP = $(this).attr('data-nip-pegawai')
                var dataIDPendidikan = $(this).attr('data-idpegawai-pendidikan')
                var dataJenisDiklat = $(this).attr('data-jenis-diklat')
                var actionDel = "{{ url('kompetensi-asn/delete') }}" + "/" + dataNIP + "/" +
                    dataIDPendidikan + "/" + dataJenisDiklat;
                $("#form-delete-asn").attr("action", actionDel);
                $("#text-item-delete-asn").text("Data Kompetensi ASN");
            });
        });
</script>

@include('partials.modal_delete')
<script>
    $('.table').on('click', function() {
            $('.icon-delete').click(function() {
                $('#deletemodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });
                var idLapor = $(this).attr('data-id-delete');
                var actionDel = "{{ url('laporan/destroy') }}" + "/" + idLapor;
                $("#form-delete").attr("action", actionDel);
                $("#text-item-delete").text("Laporan Bang Kom");
            });
        });
</script>

<script>
    $('.table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
</script>
@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Berhasil', "{{ session('success') }}", 'success');
</script>
@endif
@endpush
