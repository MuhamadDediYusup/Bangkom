@extends('layout.main-layout')
@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan') }}" method="get">
                    <div class="row">
                        <label class="col-sm-2 col-form-label" for="nama">Pencarian</label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control select2" name="id_perangkat_daerah" {{
                                    auth()->user()->hasRole('admin_pd') == true ?
                                    "disabled" : '' }}>
                                    <option value="">..Perangkat Daerah..</option>
                                    @foreach ($perangkat_daerah->Data as $item)
                                    <option value="{{ $item->id_perangkat_daerah }}" @if (auth()->
                                        user()->hasRole('admin_pd'))
                                        {{$item->id_perangkat_daerah==checkIdPerangkatDaerah() ? 'selected' : ''
                                        }}
                                        @endif {{ !empty($laporan) && $item->
                                        id_perangkat_daerah == $_GET['id_perangkat_daerah'] ? 'selected' : '' }}>
                                        {{ $item->id_perangkat_daerah }} .
                                        {{ $item->perangkat_daerah }}
                                    </option>
                                    @endforeach
                                </select>
                                @if (auth()->user()->hasRole('admin_pd'))
                                <input type="hidden" name="id_perangkat_daerah" value="{{ checkIdPerangkatDaerah() }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" type="search" id="param" name="nama"
                                    value="{{ !empty($laporan) ? $_GET['nama'] : '' }}" placeholder="..Nama atau NIP.."
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" id="button-cari" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($laporan))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-2" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">NIP</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Perangkat Daerah</th>
                                        <th class="text-center">Bang Kom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($laporan->Status == 'Success|True')
                                    @foreach ($laporan->Data as $item)
                                    <tr>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td class="text-center"><a class="modal-nip" href="javascript:void(0)"
                                                data-nip="{{ $item->nip }}">{{
                                                $item->nip}}</a></td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->perangkat_daerah }}</td>
                                        <td><a href="{{ route('laporan.create',['nip' => $item->nip,'nama' => $item->nama_lengkap]) }}"
                                                class="btn btn-success">Laporan</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @endif
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
@include('partials.preload')
@include('partials.modal_detail_asn')
@endpush
