@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <div class="flex-grow-1 bd-highlight">
                                <h4>Rekapitulasi Laporan Berdasarkan Status</h4>
                            </div>
                            <div class="bd-highlight">
                                <form action="{{ route('rekapitulasi.pd') }}" method="GET">
                                    <select name="tahun" id="tahun" class="form-control" onchange="this.form.submit()">
                                        <option value="" {{ $tahunSelected=="" ? 'selected' : '' }}>Pilih Tahun</option>
                                        @foreach ($tahun as $item)
                                        <option value="{{ $item }}" {{ $item==$tahunSelected ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-2" id="table-skpd">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Perangkat Daerah</th>
                                            <th class="text-center">Ditinjau
                                            </th>
                                            <th class="text-center">Disetujui
                                            </th>
                                            <th class="text-center">Ditolak</th>
                                            <th class="text-center text-bold">Jumlah
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td class="text-center">#</td>
                                            <td><b>Total</b></td>
                                            <td class="text-center"><b>{{ $total_laporan_ditinjau }}</b></td>
                                            <td class="text-center"><b>{{ $total_laporan_disetujui }}</b></td>
                                            <td class="text-center"><b>{{ $total_laporan_ditolak }}</b></td>
                                            <td class="text-center"><b>{{ $total_row_count }}</b></td>
                                        </tr>

                                        @foreach ($laporanPerSKPD as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item['nama_perangkat_daerah'] }}</td>
                                            <td class="text-center">
                                                {{ $item['laporan_ditinjau'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item['laporan_disetujui'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item['laporan_ditolak'] }}
                                            </td>
                                            <td class="text-center">
                                                <b>{{ $item['row_count'] }}</b>
                                            </td>
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
        </div>
    </section>
</div>

@push('js')
<script>
    $(document).ready(function() {
                $('#table-skpd').DataTable({
                    "bDestroy": true,
                    "ordering": false,
                    "paging": false,
                    // "searching": false,
                    "language": {
                        "emptyTable": "Data tidak tersedia"
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });
</script>
@endpush

@push('css')
<style>
    div.dt-buttons {
        position: absolute;
        float: right;
    }
</style>
@endpush
@endsection