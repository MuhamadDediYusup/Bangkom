@extends('layout.main-layout')
@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('form_pengiriman') }}" method="get">
                    <div class="row">
                        <label class="col-sm-2 col-form-label" for="nama">Pencarian</label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control select2" name="id_perangkat_daerah">
                                    <option value="">..Perangkat Daerah..</option>
                                    @foreach ($perangkat_daerah->Data as $item)
                                    <option value="{{ $item->id_perangkat_daerah }}" {{ !empty($pengiriman) && $item->
                                        id_perangkat_daerah == $_GET['id_perangkat_daerah'] ? 'selected' : '' }}>
                                        {{ $item->id_perangkat_daerah }} .
                                        {{ $item->perangkat_daerah }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" type="search" id="param" name="nama"
                                    value="{{ !empty($pengiriman) ? $_GET['nama'] : '' }}" placeholder="..Nama atau NIP.."
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

        @if (isset($pengiriman))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-2">
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
                                    @if ($pengiriman->Status == 'Success|True')
                                    @foreach ($pengiriman->Data as $item)
                                    <tr>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td class="text-center"><a class="modal-nip" href="javascript:void(0)"
                                                data-nip="{{ $item->nip }}">{{
                                                $item->nip}}</a></td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->perangkat_daerah }}</td>
                                        <td><a href="{{ route('pengiriman.create',['nip' => $item->nip,'nama' => $item->nama_lengkap]) }}"
                                                class="btn btn-success">Pengiriman</a>
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


@endsection

@push('js')
@if (session('error'))
@include('partials.error_alert')
@endif

@include('partials.modal_detail_asn')
@include('partials.preload')
@endpush