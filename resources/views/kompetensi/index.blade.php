@extends('layout.main-layout')

@section('content')
@include('partials.section_header')
<section>
    <div class="section-body">
        <div class="card">
            <form action="{{ route('kompetensi.index') }}" method="get">
                <div class="card-body">
                    <div class="row">
                        <label for="perangkat_daerah" class="col-sm-2 col-form-label my-auto text-dark">Perangkat
                            Daerah</label>
                        <div class="
                            @can('kompetensi-filter-option')
                                col-md-8
                            @endcan

                            @cannot('kompetensi-filter-option')
                                col-md-9
                            @endcannot
                        "> <select class="form-control select2" id="param" name="id_perangkat_daerah" required
                                disabled>
                                <option value="">..Perangkat Daerah..</option>
                                @foreach (getPerangkatDaerah()->Data as $item)
                                <option value="{{ $item->id_perangkat_daerah }}" @can('search-option-disabled') {{
                                    $item->id_perangkat_daerah == getIdPerangkatDaerahTwoDigit() ? 'selected' : '' }}
                                    @endcan
                                    @if (isset($id_perangkat_daerah)) {{ $id_perangkat_daerah ==
                                    substr($item->id_perangkat_daerah, 0, 2) ? 'selected' : '' }} @endif>
                                    {{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }}</option>
                                @endforeach
                            </select>
                            @can('search-option-disabled')
                            <input type="hidden" name="id_perangkat_daerah" value="{{ checkIdPerangkatDaerah() }}">
                            @endcan
                        </div>

                        <div class="btn-group
                                                @can('kompetensi-filter-option')
                                                    col-md-2
                                                @endcan

                                                @cannot('kompetensi-filter-option')
                                                    col-md-1
                                                @endcannot
                                            " role="group" aria-label="Basic checkbox toggle button group">

                            @can('kompetensi-filter-option')
                            <a href="javascript:void(0)"
                                class="btn btn-outline-primary pt-2 @if (session()->has('filter_nama_diklat') || session()->has('filter_tanggal')) text-warning @endif"
                                id="btn-filter"><i class="fa-solid fa-filter"></i>
                                Filter
                            </a>
                            @endcan

                            <button type="submit" id="button-cari" class="btn btn-primary pt-2">Cari</button>
                        </div>
                    </div>
                    @can('kompetensi-filter-option')
                    <div class="d-none" id="filter-laporan">
                        <div class="row">
                            <label for="perangkat_daerah" class="col-sm-2 col-form-label my-auto text-dark mt-2">Nama
                                Diklat</label>
                            <div class="col-md-5">
                                <div class="input-group mt-2">
                                    <input type="search" name="filter_nama_diklat" class="form-control"
                                        autocomplete="off" value="{{ session()->get('filter_nama_diklat') }}"
                                        placeholder="..Nama Diklat..">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group mt-2" id="kt_daterangepicker_2">
                                    <input type="search" name="filter_tanggal" class="form-control" autocomplete="off"
                                        value="{{ session()->get('filter_tanggal') }}" placeholder="..Tgl STTPP..">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>

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

            </form>
        </div>
    </div>

    @if (isset($kompetensi))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <h4>Daftar Kompetensi</h4>
                    </div>
                    <div class="p-2 bd-highlight ">



                        @can('export-excel')
                        <a href="{{ route('kompetensi.cetak', session()->get('id_perangkat_daerah')) }}"
                            class="btn btn-primary me-5"><i class="fa-regular fa-file-excel fa-beat"></i>
                            &nbsp;
                            Export Excel</a>
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
                                    <th class="text-center">Jenis Diklat</th>
                                    <th class="text-center">Nama Diklat</th>
                                    <th class="text-center">JP</th>
                                    <th class="text-center">Nomor STTPP</th>
                                    <th class="text-center">Tanggal STTPP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kompetensi as $item)
                                <tr>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>
                                        <a class="modal-nip" href="javascript:void(0)" data-nip="{{ $item->nip }}">{{
                                            $item->nip }}</a>
                                    </td>
                                    <td>{{ $item->jabatan }}</td>
                                    <td>{{ $item->jenis_diklat }}</td>
                                    <td>{{ $item->nama_diklat }}</td>
                                    <td>{{ $item->lama_pendidikan }}</td>
                                    <td>{{ $item->nomor_sttpp }}</td>
                                    <td>{{ $item->tgl_sttpp }}</td>
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

@endsection

@push('js')
@can('search-option-enabled')
<script>
    $('#param').prop('disabled', false);
</script>
@endcan

@include('partials.preload')
@include('partials.modal_detail_asn')
@endpush