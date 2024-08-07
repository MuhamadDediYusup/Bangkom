@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">

    {{-- error message --}}
    @if (session('error'))
    @include('partials.error_alert')
    @endif


    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <div class="flex-grow-1 bd-highlight">
                                <h4>Rekapitulasi Laporan Berdasarkan Verifikasi</h4>
                            </div>
                            <div class="bd-highlight">
                                <form action="{{ route('rekapitulasi.waktu') }}" method="GET">
                                    <div class="form-row">
                                        <div class="col">
                                            <select name="tahun" id="tahun" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="" {{ $tahun=="" ? 'selected' : '' }}>Pilih Tahun</option>
                                                @for ($i = date('Y'); $i >= 2022; $i--)
                                                <option value="{{ $i }}" {{ $i==$tahun ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select name="bulan" id="bulan" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="" {{ $bulan=="" ? 'selected' : '' }}>Pilih Bulan</option>
                                                @php
                                                $bulanList = [
                                                '01' => 'Januari',
                                                '02' => 'Februari',
                                                '03' => 'Maret',
                                                '04' => 'April',
                                                '05' => 'Mei',
                                                '06' => 'Juni',
                                                '07' => 'Juli',
                                                '08' => 'Agustus',
                                                '09' => 'September',
                                                '10' => 'Oktober',
                                                '11' => 'November',
                                                '12' => 'Desember'
                                                ];
                                                @endphp
                                                @foreach ($bulanList as $key => $value)
                                                <option value="{{ $key }}" {{ $key==$bulan ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
                                    <th>Tanggal</th>
                                    {{-- <th class="text-center">Ditinjau</th> --}}
                                    <th class="text-center">Disetujui
                                    </th>
                                    <th class="text-center">Perlu Perbaikan
                                    </th>
                                    <th class="text-center">Ditolak</th>
                                    <th class="text-center text-bold">Jumlah
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($laporanPerWaktu as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item['waktu'])->format('d-m-Y') }}</td>
                                    {{-- <td class="text-center">
                                        {{ $item['laporan_ditinjau'] }}
                                    </td> --}}
                                    <td class="text-center">
                                        {{ $item['laporan_disetujui'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['laporan_ditolak'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['laporan_perbaikan'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['row_count'] }}
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