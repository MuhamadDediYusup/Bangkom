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

    <!-- Helpdesk -->
    <div class="alert bg-white text-dark shadow-sm border-0 p-3 rounded" role="alert" style="border-left: 5px solid;">
        <h6 class="mb-1">Pusat Bantuan</h6>
        <hr class="mt-1 mb-2" style="border-top: 1px solid #EEEEEE;">
        <p class="mb-1">
            <i class="fab fa-whatsapp text-success"></i>
            WhatsApp: <a href="https://wa.me/6285786166466" target="_blank" class="text-primary">0857-8616-6466</a>
        </p>
        <p class="mb-0">
            <i class="fab fa-instagram text-danger"></i>
            Instagram: <a href="https://www.instagram.com/abangkomandan" target="_blank"
                class="text-primary">@abangkomandan</a>
        </p>
    </div>

    <div class="row">
        {{-- Total --}}
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
    {{-- end total --}}

    <div class="row">
        {{-- Count Usulan --}}
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Total Usulan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikUsulan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Pengiriman Bulanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikPengirimanBulanan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Total Laporan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikLaporan"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Usulan Bulanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikUsulanBulanan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Total Pengiriman</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikPengiriman"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Laporan Bulanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statistikLaporanBulanan"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('js')
{{-- import datatable --}}
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
{{-- <script src="{{ asset('assets/js/dashboard.js') }}">
    --}}
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

<script>
    var ctx = document.getElementById("statistikPengirimanBulanan");
        ctx.height = 150;
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($listMonthPengiriman),
                datasets: [{
                        label: "Pengiriman",
                        borderColor: "rgba(0,0,0,.09)",
                        borderWidth: "1",
                        backgroundColor: "rgba(0,0,0,.07)",
                        data: @json($listPengirimanDilaksanakan),
                    },
                    {
                        label: "Selesai",
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        borderWidth: "1",
                        backgroundColor: "rgba(0, 123, 255, 0.5)",
                        pointHighlightStroke: "rgba(26,179,148,1)",
                        data: @json($listPengirimanSelesai)
                    }
                ]
            },
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                }
            }
        });
</script>

<script>
    var ctx = document.getElementById("statistikPengiriman");
        ctx.height = 150;
        var myChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                datasets: [{
                    data: [@json($countPengirimanDilaksanakan), @json($countPengirimanSelesai)],
                    backgroundColor: ["rgba(0, 123, 255,0.9)", "rgba(0,0,0,0.2)"]
                }],
                labels: ["Pengiriman", "Selesai"]
            },
            options: {
                responsive: true
            }
        });
</script>
@endpush

@push('css')
<!-- Custom CSS for animations and styling -->
<style>
    .alert {
        position: relative;
        animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert p a {
        text-decoration: none;
        font-weight: bold;
        position: relative;
        transition: color 0.3s ease, transform 0.3s ease, text-decoration 0.3s ease;
    }

    .alert p a:hover {
        color: #000;
        transform: scale(1.1);
        text-decoration: underline;
    }
</style>
@endpush