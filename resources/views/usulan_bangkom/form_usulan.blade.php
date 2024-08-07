@extends('layout.main-layout')
@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        @if (session()->has('info'))
        <div class="alert alert-primary alert-dismissible show fade hide">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {!! session('info') !!}
            </div>
        </div>
        @endif

        @push('js')
        <script>
            window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 10000);
        </script>
        @endpush
        <div class="card">
            <div class="card-body">
                <form action="{{ route('form_usulan') }}" method="get">
                    <div class="row">
                        <label class="col-sm-2 col-form-label" for="param">Pencarian</label>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control select2" id="perangkat_daerah" name="id_perangkat_daerah"
                                    disabled>
                                    <option value="">..Perangkat Daerah..</option>
                                    @foreach ($perangkat_daerah->Data as $item)
                                    <option value="{{ $item->id_perangkat_daerah }}" @can('search-option-disabled') {{
                                        $item->id_perangkat_daerah == getIdPerangkatDaerahTwoDIgit() ? 'selected' : ''
                                        }}>
                                        @endcan
                                        @cannot('search-option-disabled')
                                        {{ (!empty($usulan) && $item->id_perangkat_daerah ==
                                        $_GET['id_perangkat_daerah'] || $item->id_perangkat_daerah ==
                                        session()->get('id_perangkat_daerah')) ? 'selected' : '' }}>
                                        @endcannot
                                        {{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }} </option>
                                    @endforeach
                                </select>
                                @can('search-option-disabled')
                                <input type="hidden" name="id_perangkat_daerah" value="{{ checkIdPerangkatDaerah() }}">
                                @endcan
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" type="search" id="param" name="nama"
                                    value="{{ !empty($usulan) ? $_GET['nama'] : '' }}" placeholder="..Nama atau NIP.."
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

        @if (isset($usulan))
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
                                    @foreach ($usulan as $item)
                                    <tr>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td class="text-center"><a class="modal-nip" href="javascript:void(0)"
                                                data-nip="{{ $item->nip }}">{{ $item->nip }}</a></td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->perangkat_daerah }}</td>
                                        <td>
                                            <a href="{{ route('usulan_bangkom.create', [$item->nip]) }}"
                                                class="btn btn-success">Usulan</a>
                                        </td>
                                    </tr>
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


@endsection

@push('js')
@if (session('error'))
@if (empty($usulan->Data))
@include('partials.error_alert')
@endif
@endif
@include('partials.modal_detail_asn') @include('partials.preload')
@can('search-option-enabled')
<script>
    $('#perangkat_daerah').prop('disabled', false);
</script>
@endcan
@endpush