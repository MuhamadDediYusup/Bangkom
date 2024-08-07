<div class="card">
    <div class="card-body">
        <div class="row">
            <label for="param" class="col-sm-2 col-form-label my-auto text-dark">Pencarian</label>
            <div class="col-md-5">
                <select class="form-control select2" id="perangkat_daerah" name="id_perangkat_daerah" disabled>
                    <option value="">..Perangkat Daerah..</option>
                    @foreach (getPerangkatDaerah()->Data as $item)
                    <option value="{{ $item->id_perangkat_daerah }}" @can('search-option-disabled') {{ $item->
                        id_perangkat_daerah == getIdPerangkatDaerahTwoDigit() ? 'selected' : '' }}
                        @endcan
                        @if (isset($id_perangkat_daerah)) {{ $id_perangkat_daerah == $item->id_perangkat_daerah ?
                        'selected' : '' }} @endif>
                        {{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }}
                    </option>
                    @endforeach
                </select>
                @can('search-option-disabled')
                <input type="hidden" name="id_perangkat_daerah" value="{{ checkIdPerangkatDaerah() }}">
                @endcan
            </div>
            <div class="col-md-4">
                <input class="param form-control" type="search" id="param" name="nama_nip"
                    placeholder="..Nama atau NIP.." value="{{ isset($nama_nip) ? $nama_nip : '' }}"
                    @can('personal-locked') disabled @endcan required></input>
            </div>
            <div class="col-1">
                <button type="submit" id="button-cari" class="btn btn-primary pb-2 ">Cari</button>
            </div>
        </div>
    </div>
</div>

@push('js')

@include('partials.preload')

@can('search-option-enabled')
<script>
    $('#perangkat_daerah').prop('disabled', false);
</script>
@endcan
@endpush
