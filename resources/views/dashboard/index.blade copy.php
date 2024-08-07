@extends('layout.main-layout')

@section('content')
    <div class="section-header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Welcome</h2>
        <p class="section-lead">
            Selamat Datang di Aplikasi Pengembangan Kompetensi ASN di Sleman (ABANGKOMANDAN)
        </p>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fa fa-file-pen" style="color:white; font-size: 1.2rem"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Usulan</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_usulan }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fa-regular fa-paper-plane" style="color:white; font-size: 1.2rem"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pengiriman</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_pengiriman }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fa-solid fa-graduation-cap" style="color:white; font-size: 1.2rem"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Laporan</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_laporan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Usulan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statistikUsulan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Usulan Bulanan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statistikUsulanBulanan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Laporan Bulanan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statistikLaporanBulanan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Laporan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statistikLaporan"></canvas>
                    </div>
                </div>
            </div>


            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Jumlah Laporan Per SKPD</h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2" id="table-skpd">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>SKPD</th>
                                        <th class="text-center text-bold">Jumlah Laporan</th>
                                        <th class="text-center text-bold">Jumlah Pegawai Laporan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanPerSKPD as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item['nama_perangkat_daerah'] }}</td>
                                            <td class="text-center"><b>{{ $item['row_count'] }}</b></td>
                                            <td class="text-center"><b>{{ $item['pegawai_row_count'] }}</b></td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Jumlah Laporan Per Pegawai</h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>SKPD</th>
                                        <th class="text-center text-bold">Jumlah Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanPerSKPD as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item['nama_perangkat_daerah'] }}</td>
                                            <td class="text-center"><b>{{ $item['pegawai_row_count'] }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div> --}}

        </div>
    </div>
@endsection
@push('js')
    {{-- import datatable --}}
    <script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/dashboard.js') }}"> --}}
    <script>
        "use strict";

        //---Statistik Usulan---
        var ctx = document.getElementById("statistikUsulan").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "doughnut",
            data: {
                datasets: [{
                    data: [{{ $usulanDitinjau }}, {{ $usulanDisetujui }}, {{ $usulanDitolak }}],
                    backgroundColor: ["#ffa426", "#63ed7a", "#fc544b"],
                    label: "Dataset 1",
                }, ],
                labels: ["Ditinjau", "Disetujui", "Ditolak"],
            },
            options: {
                responsive: true,
                legend: {
                    position: "bottom",
                },
            },
        });

        $(document).ready(function() {
            $('.table').dataTable({
                "bDestroy": true,
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "lengthChange": false,

                // "columnDefs": [{
                //     "sortable": false,
                //     "targets": [0, 1, 2, 3, 4, 5, 6]
                // }]
            });
        });

        //---Statistik Usulan Bulanan---
        var ctx = document.getElementById("statistikUsulanBulanan").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: @json($listMonthUsulan),
                datasets: [{
                    label: "Statistics",
                    data: @json($listDataUsulan),
                    borderWidth: 2,
                    backgroundColor: "#6777ef",
                    borderColor: "#6777ef",
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                }, ],
            },
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 20,
                        },
                    }, ],
                    xAxes: [{
                        ticks: {
                            display: true,
                        },
                        gridLines: {
                            display: false,
                        },
                    }, ],
                },
            },
        });

        //---Statistik Laporan Bulanan---
        var ctx = document.getElementById("statistikLaporanBulanan").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: @json($listMonthLaporan),
                datasets: [{
                    label: "Statistics",
                    data: @json($listDataLaporan),
                    borderWidth: 2,
                    backgroundColor: "#6777ef",
                    borderColor: "#6777ef",
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                }, ],
            },
            options: {
                legend: {
                    display: false,
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 20,
                        },
                    }, ],
                    xAxes: [{
                        ticks: {
                            display: true,
                        },
                        gridLines: {
                            display: false,
                        },
                    }, ],
                },
            },
        });

        //---Statistik Laporan---
        var ctx = document.getElementById("statistikLaporan").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "pie",
            data: {
                datasets: [{
                    data: [{{ $laporanDitinjau }}, {{ $laporanDisetujui }}, {{ $laporanDitolak }}],
                    backgroundColor: ["#ffa426", "#63ed7a", "#fc544b"],
                    label: "Dataset 1",
                }, ],
                labels: ["Ditinjau", "Disetujui", "Ditolak"],
            },
            options: {
                responsive: true,
                legend: {
                    position: "bottom",
                },
            },
        });
    </script>
@endpush
