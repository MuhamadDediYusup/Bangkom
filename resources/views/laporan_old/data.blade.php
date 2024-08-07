@extends('layout.main-layout')
@section('content')
@include('partials.section_header')

{{-- @dd($ditinjau[0]->status, $ditolak, $disetujui) --}}

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>Daftar Laporan Pengembangan Kompetensi</h4>
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
                                    <a class="dropdown-item">Fasilitasi BKPP</a>
                                    <a class="dropdown-item" href="{{ route('laporan_2022.form_laporan') }}">Fasilitasi
                                        Perangkat Daerah</a>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ditinjau-tab" data-toggle="tab" href="#ditinjau"
                                    role="tab" aria-controls="ditinjau" aria-selected="true"><b>Ditinjau <sup>[{{
                                            $row_count['ditinjau'] }}]</sup></b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="disetujui-tab" data-toggle="tab" href="#disetujui" role="tab"
                                    aria-controls="disetujui" aria-selected="false"><b>Disetujui <sup>[{{
                                            $row_count['disetujui'] }}]</sup></b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ditolak-tab" data-toggle="tab" href="#ditolak" role="tab"
                                    aria-controls="ditolak" aria-selected="false"><b>Ditolak <sup>[{{
                                            $row_count['ditolak'] }}]</sup></b></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="ditinjau" role="tabpanel"
                                aria-labelledby="ditinjau-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                {{-- <th class="text-center">NIP</th> --}}
                                                <th>Jenis Diklat</th>
                                                {{-- <th class="text-center">Sub Jenis Diklat</th> --}}
                                                {{-- <th class="text-center">Rumpun Diklat</th> --}}
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                @can('laporan-edit', 'laporan-delete')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ditinjau as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_lengkap }} <br>
                                                    NIP. <a class="modal-nip" href="javascript:void(0)"
                                                        data-nip="{{ $item->nip }}">{{
                                                        $item->nip}}</a></td>
                                                {{-- <td class="text-center"></td> --}}
                                                <td>{{ $item->jenis_diklat }} <br> {{ $item->sub_jenis_diklat }}</td>
                                                {{-- <td></td> --}}
                                                {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                                <td>{{ $item->nama_diklat }}</td>
                                                <td class="text-center">{{ $item->lama_pendidikan }}</td>
                                                <td class="text-center">{{
                                                    Carbon\Carbon::parse($item->tgl_sttpp)->format('d-m-Y') }}</td>
                                                {{-- <td class="text-center">
                                                    <span data-toggle="tooltip" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->entry_time)->format('H:i') }} WIB">
                                                        {{ Carbon\Carbon::parse($item->entry_time)->format('d-m-Y') }}
                                                    </span>
                                                </td> --}}
                                                <td class="text-center">
                                                    @if ($item->status_laporan == "0")
                                                    <span class="badge badge-danger" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB : ''{{ $item->alasan }}''">
                                                        Ditolak
                                                    </span>
                                                    @elseif ($item->status_laporan =="1")
                                                    <span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Disetujui</span>
                                                    @elseif ($item->status_laporan == "9")
                                                    <span class="badge badge-dark" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Dilaksanakan</span>
                                                    @else
                                                    <span class="badge badge-warning" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Ditinjau</span>
                                                    @endif
                                                </td>
                                                @can('laporan-edit', 'laporan-delete')
                                                <td class="text-center">
                                                    @can('laporan-edit')
                                                    <a href="{{ route('laporan.edit', [$item->nip, $item->id_lapor]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Edit Laporan"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('laporan-delete')
                                                    <a class="icon-delete" href="javascript:void(0)"
                                                        data-id-delete="{{ $item->id_lapor }}" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Hapus Laporan">
                                                        <i class="fa-solid fa-trash-can text-danger"></i></a>
                                                    @endcan
                                                </td>
                                                @endcan
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="disetujui" role="tabpanel" aria-labelledby="disetujui-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                {{-- <th class="text-center">NIP</th> --}}
                                                <th>Jenis Diklat</th>
                                                {{-- <th class="text-center">Sub Jenis Diklat</th> --}}
                                                {{-- <th class="text-center">Rumpun Diklat</th> --}}
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                @can('laporan-approve')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($disetujui as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_lengkap }} <br>
                                                    NIP. <a class="modal-nip" href="javascript:void(0)"
                                                        data-nip="{{ $item->nip }}">{{
                                                        $item->nip}}</a></td>
                                                {{-- <td class="text-center"></td> --}}
                                                <td>{{ $item->jenis_diklat }} <br> {{ $item->sub_jenis_diklat }}</td>
                                                {{-- <td></td> --}}
                                                {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                                <td>{{ $item->nama_diklat }}</td>
                                                <td class="text-center">{{ $item->lama_pendidikan }}</td>
                                                <td class="text-center">{{
                                                    Carbon\Carbon::parse($item->tgl_sttpp)->format('d-m-Y') }}</td>
                                                {{-- <td class="text-center">
                                                    <span data-toggle="tooltip" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->entry_time)->format('H:i') }} WIB">
                                                        {{ Carbon\Carbon::parse($item->entry_time)->format('d-m-Y') }}
                                                    </span>
                                                </td> --}}
                                                <td class="text-center">
                                                    @if ($item->status_laporan == '0')
                                                    <span class="badge badge-danger" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB : ''{{ $item->alasan }}''">
                                                        Ditolak
                                                    </span>
                                                    @elseif ($item->status_laporan == '1')
                                                    <span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Disetujui</span>
                                                    @elseif ($item->status_laporan == '9')
                                                    <span class="badge badge-dark" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Dilaksanakan</span>
                                                    @else
                                                    <span class="badge badge-warning" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Ditinjau</span>
                                                    @endif
                                                </td>
                                                @can('laporan-approve')
                                                <td class="text-center">
                                                    @can('laporan-edit')
                                                    <a href="{{ route('laporan.edit', [$item->nip, $item->id_lapor]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Edit Laporan"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('laporan-delete')
                                                    <a class="icon-delete" href="javascript:void(0)"
                                                        data-id-delete="{{ $item->id_lapor }}" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Hapus Laporan">
                                                        <i class="fa-solid fa-trash-can text-danger"></i></a>
                                                    @endcan
                                                </td>
                                                @endcan
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                {{-- <th class="text-center">NIP</th> --}}
                                                <th>Jenis Diklat</th>
                                                {{-- <th class="text-center">Sub Jenis Diklat</th> --}}
                                                {{-- <th class="text-center">Rumpun Diklat</th> --}}
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Alasan</th>
                                                @can('laporan-approve')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ditolak as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_lengkap }} <br>
                                                    NIP. <a class="modal-nip" href="javascript:void(0)"
                                                        data-nip="{{ $item->nip }}">{{
                                                        $item->nip}}</a></td>
                                                {{-- <td class="text-center"></td> --}}
                                                <td>{{ $item->jenis_diklat }} <br> {{ $item->sub_jenis_diklat }}</td>
                                                {{-- <td></td> --}}
                                                {{-- <td>{{ $item->rumpun_diklat }}</td> --}}
                                                <td>{{ $item->nama_diklat }}</td>
                                                <td class="text-center">{{ $item->lama_pendidikan }}</td>
                                                <td class="text-center">{{
                                                    Carbon\Carbon::parse($item->tgl_sttpp)->format('d-m-Y') }}</td>
                                                {{-- <td class="text-center">
                                                    <span data-toggle="tooltip" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->entry_time)->format('H:i') }} WIB">
                                                        {{ Carbon\Carbon::parse($item->entry_time)->format('d-m-Y') }}
                                                    </span>
                                                </td> --}}
                                                <td class="text-center">
                                                    @if ($item->status_laporan == 0)
                                                    <span class="badge badge-danger" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB : ''{{ $item->alasan }}''">
                                                        Ditolak
                                                    </span>
                                                    @elseif ($item->status_laporan == '1')
                                                    <span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Disetujui</span>
                                                    @elseif ($item->status_laporan == '9')
                                                    <span class="badge badge-dark" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Dilaksanakan</span>
                                                    @else
                                                    <span class="badge badge-warning" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Ditinjau</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->alasan_tolak }}</td>
                                                @can('laporan-approve')
                                                <td class="text-center">
                                                    @can('laporan-edit')
                                                    <a href="{{ route('laporan.edit', [$item->nip, $item->id_lapor]) }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Edit Laporan"><i
                                                            class="fa-regular fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('laporan-delete')
                                                    <a class="icon-delete" href="javascript:void(0)"
                                                        data-id-delete="{{ $item->id_lapor }}" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="Hapus Laporan">
                                                        <i class="fa-solid fa-trash-can text-danger"></i></a>
                                                    @endcan
                                                </td>
                                                @endcan
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
    </section>
</div>

@endsection
@push('js')
@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Sukses', "{{ session('success') }}", 'success');
</script>
@endif

@include('partials.preload')
@include('partials.modal_detail_asn')

@include('partials.modal_delete')
<script>
    $(document).ready(function() {
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
@endpush