@extends('layout.main-layout')

@section('content')
@include('partials.section_header')
<div class="section-body">

    <form action="{{ route('jpbangkom.index') }}" method="GET">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <label for="param" class="col-sm-2 col-form-label my-auto text-dark">Pencarian</label>
                    <div class="col-md-8">
                        <select class="form-control select2" id="param" name="jam" required>
                            <option value="">..Jam Pelajaran..</option>
                            <option value="Semua" @isset($jam) {{ $jam=='Semua' ? 'selected' : '' }} @endisset>
                                Semua</option>
                            <option value="Belum" @isset($jam) {{ $jam=='Belum' ? 'selected' : '' }} @endisset>
                                Jam Pelajaran = 0</option>
                            <option value="Kurang" @isset($jam) {{ $jam=='Kurang' ? 'selected' : '' }} @endisset>
                                Jam Pelajaran : 01-19 Jam</option>
                            <option value="Sudah" @isset($jam) {{ $jam=='Sudah' ? 'selected' : '' }} @endisset>
                                Jam Pelajaran : Minimal 20 Jam</option>
                        </select>
                    </div>

                    <div class="btn-group col-md-2" role="group" aria-label="Basic checkbox toggle button group">
                        <a href="javascript:void(0)"
                            class="btn btn-outline-primary pt-2 @if (session()->has('filter_nama_diklat') || session()->has('filter_tanggal')) text-warning @endif"
                            id="btn-filter"><i class="fa-solid fa-filter"></i>
                            Filter
                        </a>
                        <button type="submit" id="button-cari" class="btn btn-primary pt-2">Cari</button>
                    </div>
                </div>

                <div class="d-none" id="filter-laporan">
                    <div class="row mt-2">
                        <label for="perangkat_daerah" class="col-sm-2 col-form-label my-auto text-dark mt-2">Perangkat
                            Daerah</label>
                        <div class="col-md-5">
                            <select class="form-control select2" id="perangkat_daerah" name="id_perangkat_daerah"
                                disabled>
                                <option value="">..Perangkat Daerah..</option>
                                @foreach (getPerangkatDaerah()->Data as $item)
                                <option value="{{ $item->id_perangkat_daerah }}" @can('search-option-disabled')
                                    {{$item->id_perangkat_daerah==getIdPerangkatDaerahTwoDIgit() ? 'selected' : '' }}
                                    @endcan @isset($id_perangkat_daerah) {{ $item->id_perangkat_daerah ==
                                    $id_perangkat_daerah ? 'selected' : '' }}
                                    @endisset >{{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }}
                                </option>
                                @endforeach
                            </select>
                            @can('search-option-disabled')
                            <input type="hidden" name="id_perangkat_daerah" value="{{ checkIdPerangkatDaerah() }}">
                            @endcan
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" id="kt_daterangepicker_2">
                                <input type="search" name="filter_tanggal" class="form-control" autocomplete="off"
                                    value="{{ session()->get('filter_tanggal') }}" placeholder="..Tgl STTPP..">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @isset($jp)
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="p-2 bd-highlight ">
                            @can('export-excel')
                            <div class="text-end">
                                <a href="{{ route('jpbangkom.cetak', [$id_perangkat_daerah == null ? '00' : $id_perangkat_daerah, $jam] ) }}"
                                    class="btn btn-primary me-5 mb-2"><i class="fa-regular fa-file-excel fa-beat"></i>
                                    &nbsp;
                                    Export Excel</a>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">NIP</th>
                                        <th class="text-center">Jabatan</th>
                                        <th class="text-center">Perangkat Daerah</th>
                                        <th class="text-center">JP Manajerial</th>
                                        <th class="text-center">JP Teknis</th>
                                        <th class="text-center">JP Fungsional</th>
                                        <th class="text-center">JP Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jp as $item)
                                    <tr>
                                        <td> {{ $item->nama_lengkap }}</td>
                                        <td class="text-center"><a class="modal-nip" href="javascript:void(0)"
                                                data-nip="{{ $item->nip }}">{{
                                                $item->nip}}</a></td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->perangkat_daerah }}</td>
                                        <td class="text-center">{{ $item->jp_manajerial }}</td>
                                        <td class="text-center">{{ $item->jp_teknis }}</td>
                                        <td class="text-center">{{ $item->jp_fungsional }}</td>
                                        <td class="text-center fw-bold">{{ $item->jp_total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endisset

</div>
@endsection

@push('js')

@include('partials.preload')
@include('partials.modal_detail_asn')

@can('search-option-enabled')
<script>
    $('#perangkat_daerah').prop('disabled', false);
</script>
@endcan
@endpush

@push('js')
<script>
    $('#btn-filter').click(function() {
                                    if ($('#filter-laporan').hasClass('d-none')) {
                                        $('#filter-laporan').fadeIn();
                                        $('#filter-laporan').slideDown();
                                        $('#filter-laporan').removeClass('d-none');
                                        $('#btn-filter').html('<i class="fa-solid fa-times"></i> Tutup');
                                    } else {
                                        $('#filter-laporan').fadeOut();
                                        $('#filter-laporan').slideUp();
                                        $('#filter-laporan').addClass('d-none');
                                        $('#btn-filter').html('<i class="fa-solid fa-filter"></i> Filter');
                                    }
                                });
</script>
@endpush

@push('js')
<script>
    $(document).ready(function() {
                                    $('#kt_daterangepicker_2').daterangepicker({
                                        buttonClasses: ' btn',
                                        applyClass: 'btn-primary',
                                        cancelClass: 'btn-secondary',
                                        locale: {
                                            format: 'DD-MM-Y',
                                            separator: ' s/d ',
                                            applyLabel: 'Terapkan',
                                            cancelLabel: 'Batal',
                                            fromLabel: 'Dari',
                                            toLabel: 'Sampai',
                                            customRangeLabel: 'Custom',
                                            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum',
                                                'Sab'
                                            ],
                                            monthNames: ['Januari', 'Februari', 'Maret', 'April',
                                                'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                                                'Oktober', 'November', 'Desember'
                                            ],
                                            firstDay: 1
                                        }
                                    }, function(start, end, label) {
                                        $('#kt_daterangepicker_2 .form-control').val(start.format('DD-MM-Y') + ' s/d ' + end
                                            .format('DD-MM-Y'));
                                    });
                                });
</script>
@endpush
