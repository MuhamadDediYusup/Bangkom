"use strict";

//---Statistik Usulan---
var ctx = document.getElementById("statistikUsulan").getContext("2d");
var myChart = new Chart(ctx, {
    type: "doughnut",
    data: {
        datasets: [
            {
                data: [80, 50, 40, 30],
                backgroundColor: ["#191d21", "#63ed7a", "#ffa426", "#fc544b"],
                label: "Dataset 1",
            },
        ],
        labels: ["Dilaksanakan", "Disetujui", "Ditinjau", "Ditolak"],
    },
    options: {
        responsive: true,
        legend: {
            position: "bottom",
        },
    },
});

//---Statistik Usulan Bulanan---
var ctx = document.getElementById("statistikUsulanBulanan").getContext("2d");
var myChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ],
        datasets: [
            {
                label: "Statistics",
                data: [90, 80, 100, 50, 30, 20, 40, 50, 60, 70, 80, 90],
                borderWidth: 2,
                backgroundColor: "#6777ef",
                borderColor: "#6777ef",
                borderWidth: 2.5,
                pointBackgroundColor: "#ffffff",
                pointRadius: 4,
            },
        ],
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [
                {
                    gridLines: {
                        drawBorder: false,
                        color: "#f2f2f2",
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 20,
                    },
                },
            ],
            xAxes: [
                {
                    ticks: {
                        display: true,
                    },
                    gridLines: {
                        display: false,
                    },
                },
            ],
        },
    },
});

//---Statistik Laporan Bulanan---
var ctx = document.getElementById("statistikLaporanBulanan").getContext("2d");
var myChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ],
        datasets: [
            {
                label: "Statistics",
                data: [90, 80, 100, 50, 30, 20, 40, 50, 60, 70, 80, 90],
                borderWidth: 2,
                backgroundColor: "#6777ef",
                borderColor: "#6777ef",
                borderWidth: 2.5,
                pointBackgroundColor: "#ffffff",
                pointRadius: 4,
            },
        ],
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [
                {
                    gridLines: {
                        drawBorder: false,
                        color: "#f2f2f2",
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 20,
                    },
                },
            ],
            xAxes: [
                {
                    ticks: {
                        display: true,
                    },
                    gridLines: {
                        display: false,
                    },
                },
            ],
        },
    },
});

//---Statistik Laporan---
var ctx = document.getElementById("statistikLaporan").getContext("2d");
var myChart = new Chart(ctx, {
    type: "pie",
    data: {
        datasets: [
            {
                data: [80, 50, 40, 30],
                backgroundColor: ["#191d21", "#63ed7a", "#ffa426", "#fc544b"],
                label: "Dataset 1",
            },
        ],
        labels: ["Dilaksanakan", "Disetujui", "Ditinjau", "Ditolak"],
    },
    options: {
        responsive: true,
        legend: {
            position: "bottom",
        },
    },
});
