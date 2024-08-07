@extends('layout.main-layout')
@section('content')
@include('partials.section_header')
<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">

                @can('laporan-search-option')
                <div class="section-body">
                    <div class="card">
                        <form action="{{ route('laporan.index') }}" method="get">
                            <div class="card-body">
                                <div class="row">
                                    <label for="perangkat_daerah"
                                        class="col-sm-2 col-form-label my-auto text-dark">Perangkat
                                        Daerah</label>
                                    <div class="
                                                @can('laporan-filter-option')
                                                    col-md-8
                                                @endcan

                                                @cannot('laporan-filter-option')
                                                    col-md-9
                                                @endcannot
                                            ">
                                        <select class="form-control select2" id="param" name="id_perangkat_daerah"
                                            required>
                                            <option value="">..Perangkat Daerah..</option>
                                            @foreach (getPerangkatDaerah()->Data as $item)
                                            <option value="{{ $item->id_perangkat_daerah }}" {{ $item->
                                                id_perangkat_daerah == session()->get('id_perangkat_daerah') ?
                                                'selected' : '' }}>
                                                {{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }}
                                            </option>
                                            @endforeach
                                            <option value="all" {{ session()->get('id_perangkat_daerah') == 'all' ?
                                                'selected' : '' }}>
                                                00. Semua Perangkat Daerah</option>
                                        </select>
                                    </div>

                                    <div class="btn-group
                                                @can('laporan-filter-option')
                                                    col-md-2
                                                @endcan

                                                @cannot('laporan-filter-option')
                                                    col-md-1
                                                @endcannot
                                            " role="group" aria-label="Basic checkbox toggle button group">
                                        @can('laporan-filter-option')
                                        <a href="javascript:void(0)"
                                            class="btn btn-outline-primary pt-2 @if (session()->has('filter_nama_diklat') || session()->has('filter_tanggal')) text-warning @endif"
                                            id="btn-filter"><i class="fa-solid fa-filter"></i>
                                            Filter
                                        </a>
                                        @endcan

                                        <button type="submit" id="button-cari"
                                            class="btn btn-primary pt-2">Cari</button>
                                    </div>

                                </div>
                                @can('laporan-filter-option')
                                <div class="d-none" id="filter-laporan">
                                    <div class="row">
                                        <label for="perangkat_daerah"
                                            class="col-sm-2 col-form-label my-auto text-dark mt-2">Nama atau NIP</label>
                                        <div class="col-md-4">
                                            <div class="input-group mt-2">
                                                <input type="search" name="filter_nama_nip" class="form-control"
                                                    autocomplete="off" value="{{ session()->get('filter_nama_nip') }}"
                                                    placeholder="..Nama atau NIP..">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group mt-2">
                                                <input type="search" name="filter_nama_diklat" class="form-control"
                                                    autocomplete="off"
                                                    value="{{ session()->get('filter_nama_diklat') }}"
                                                    placeholder="..Nama Diklat..">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group mt-2" id="kt_daterangepicker_2">
                                                <input type="search" name="filter_tanggal" class="form-control"
                                                    autocomplete="off" value="{{ session()->get('filter_tanggal') }}"
                                                    placeholder="..Tgl STTPP..">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcan

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

                            </div>
                        </form>
                    </div>
                </div>
                @endcan

                @if (auth()->user()->cannot('laporan-search-option') || session()->get('id_perangkat_daerah') != null)
                <div class="card">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>Daftar Laporan Pengembangan Kompetensi</h4>
                        </div>
                        <div class="p-2 bd-highlight ">
                            @can('export-excel')
                            <a href="{{ route('laporan.cetak', session()->get('id_perangkat_daerah')) }}"
                                class="btn btn-primary me-5"><i class="fa-regular fa-file-excel fa-beat"></i>
                                &nbsp;
                                Export Excel</a>
                            @endcan

                            @can('laporan-create')
                            <div class="dropdown d-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton3"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-circle-plus fa-beat"></i> &nbsp; Tambah Laporan
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start"
                                    style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{ route('laporan.redirect') }}">Fasilitasi
                                        BKPP</a>
                                    <a class="dropdown-item" href="{{ route('laporan.form_laporan') }}">Fasilitasi
                                        Perangkat Daerah</a>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ditinjau-tab" data-toggle="tab" href="#ditinjau"
                                    role="tab" aria-controls="ditinjau" aria-selected="true"><b>Ditinjau
                                        <sup id="ditinjau_count">[0]</sup></b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="diperbaiki-tab" data-toggle="tab" href="#diperbaiki" role="tab"
                                    aria-controls="diperbaiki" aria-selected="false"><b>Diperbaiki
                                        <sup id="diperbaiki_count">[0]</sup></b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="disetujui-tab" data-toggle="tab" href="#disetujui" role="tab"
                                    aria-controls="disetujui" aria-selected="false"><b>Disetujui
                                        <sup id="disetujui_count">[0]</sup></b>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ditolak-tab" data-toggle="tab" href="#ditolak" role="tab"
                                    aria-controls="ditolak" aria-selected="false"><b>Ditolak
                                        <sup id="ditolak_count">[0]</sup></b></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="ditinjau" role="tabpanel"
                                aria-labelledby="ditinjau-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="lap_ditinjau">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                @can('laporan-edit', 'laporan-delete')
                                                <th class="text-center" width="8%">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                            id="ditinjau_show_to"></span> of
                                        <span id="ditinjau_total"></span>
                                    </div>
                                    <div class="p-2">
                                        <div class="paginate_button page-item next">
                                            <button id="ditinjau_next" class="page-link">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="diperbaiki" role="tabpanel" aria-labelledby="diperbaiki-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="lap_diperbaiki">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Catatan</th>
                                                @can('laporan-edit', 'laporan-delete')
                                                <th class="text-center" width="8%">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                            id="diperbaiki_show_to"></span> of
                                        <span id="diperbaiki_total"></span>
                                    </div>
                                    <div class="p-2">
                                        <div class="paginate_button page-item next">
                                            <button id="diperbaiki_next" class="page-link">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="disetujui" role="tabpanel" aria-labelledby="disetujui-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="lap_disetujui">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Catatan</th>
                                                @can('laporan-approve')
                                                @can('laporan-edit', 'laporan-delete')
                                                <th class="text-center" width="8%">Admin</th>
                                                @endcan
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                            id="disetujui_show_to"></span> of
                                        <span id="disetujui_total"></span>
                                    </div>
                                    <div class="p-2">
                                        <div class="paginate_button page-item next">
                                            <button id="disetujui_next" class="page-link">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="lap_ditolak">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th class="text-center">JP</th>
                                                <th class="text-center">STTPP</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Alasan</th>
                                                @can('laporan-approve')
                                                @can('laporan-edit', 'laporan-delete')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <div class="d-flex">
                                        <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                                id="ditolak_show_to"></span> of
                                            <span id="ditolak_total"></span>
                                        </div>
                                        <div class="p-2">
                                            <div class="paginate_button page-item next">
                                                <button id="ditolak_next" class="page-link">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Berhasil', "{{ session('success') }}", 'success');
</script>
@endif

@if (session('error'))
@include('partials.error_alert')
@endif

@include('partials.preload')
@include('partials.modal_detail_asn')
@include('partials.modal_delete')
<script>
    $('.table').on('draw.dt', function() {
            $('.icon-delete').click(function() {
                $('#deletemodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });
                var idLapor = $(this).attr('data-id-delete');
                var actionDel = "{{ url('laporan/destroy') }}" + "/" + idLapor;
                $("#form-delete").attr("action", actionDel);
                $("#text-item-delete").text("Laporan Bang Kom");
            });
        });
</script>
@endpush

@if (auth()->user()->cannot('laporan-search-option') || session()->get('id_perangkat_daerah') != null)
@push('js')
<div class="modal fade" id="preload-ajax" tabindex="-1" role="dialog" aria-labelledby="loading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content opacity-75">
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-primary mt-md-2" role="status"></div>
                    <p class="mt-2">Menampilkan Data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showPreload() {
            $('#preload-ajax').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });
        }

        function hidePreload() {
            setTimeout(function() {
                $('#preload-ajax').modal('hide');
            }, 500);
        }
</script>

{{-- Laporan Ditinjau --}}
<script>
    var start = 0;
            var end = 50;

            $.ajax({
                'url': "laporan-data/ditinjau/" + start + "/" + end,
                'method': "GET",
                'contentType': 'application/json',
                "beforeSend": showPreload,
                "complete": hidePreload,
            }).done(function(data) {
                $('#lap_ditinjau').dataTable({
                    "bDestroy": true,
                    "ordering": false,
                    stateSave: true,
                    "bAutoWidth": false,
                    "data": data.ditinjau,
                    "pageLength": 50,
                    "paging": false,
                    "info": false,
                    "columns": [{
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.nama_lengkap +
                                    "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                    row.nip +
                                    "'>" + row.nip +
                                    "</a> @can('list-all-perangkat-daerah') <br>" + row
                                    .perangkat_daerah +
                                    " @endcan"
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.jenis_diklat + "<br>" +
                                    row.sub_jenis_diklat
                            }
                        },
                        {
                            "data": "nama_diklat"
                        },
                        {
                            "data": "lama_pendidikan"
                        },
                        {
                            "render": function(data, type, row) {
                                return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.status == '3' ? "<span class='badge badge-warning badge-outlined' data-toggle='tooltip' data-placement='top' title='" +
                                    moment(row.entry_time).format('DD-MM-YYYY') +
                                    "'> Diperbaiki</span>" : "<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='" +
                                    moment(row.entry_time).format('DD-MM-YYYY') +
                                    "'> Ditinjau</span>"

                                // return "<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='" +
                                //     moment(row.entry_time).format('DD-MM-YYYY') +
                                //     "'> Ditinjau</span>"
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return "@can('laporan-edit', 'laporan-delete') @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                    row.nip + "/" + row.id_lapor + "/000" +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                    row.id_lapor +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan @endcan"
                            }
                        }
                    ],
                    'columnDefs': [{
                            "targets": 0,
                            "className": "text-center",
                        },
                        {
                            "targets": 4,
                            "className": "text-center",
                        },
                        {
                            "targets": 7,
                            "className": "text-center",
                        },
                    ],
                });
                $('#ditinjau_count, #ditinjau_total').text("[" + data.row_count + "]");
                if (data.row_count < 50) {
                    $('#ditinjau_next').hide();
                    $('#ditinjau_show_to').text(data.row_count);
                } else {
                    $('#ditinjau_show_to').text(end);
                }
            });

            $('#ditinjau_next, .page-item next').click(function() {
                start = start;
                end = end + 50;
                $.ajax({
                    'url': "laporan-data/ditinjau/" + start + "/" + end,
                    'method': "GET",
                    'contentType': 'application/json',
                    "beforeSend": showPreload,
                    "complete": hidePreload,
                }).done(function(data) {
                    $('#lap_ditinjau').dataTable({
                        "bDestroy": true,
                        "ordering": false,
                        stateSave: true,
                        "bAutoWidth": false,
                        "data": data.ditinjau,
                        "pageLength": 50,
                        "paging": false,
                        "info": false,
                        "columns": [{
                                "render": function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.nama_lengkap +
                                        "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                        row.nip +
                                        "'>" + row.nip +
                                        "</a> @can('list-all-perangkat-daerah') <br>" + row
                                        .perangkat_daerah +
                                        " @endcan"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.jenis_diklat + "<br>" +
                                        row.sub_jenis_diklat
                                }
                            },
                            {
                                "data": "nama_diklat"
                            },
                            {
                                "data": "lama_pendidikan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='" +
                                        moment(row.entry_time).format('DD-MM-YYYY') +
                                        "'> Ditinjau</span>"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "@can('laporan-edit', 'laporan-delete') @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                        row.nip + "/" + row.id_lapor + "/000" +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                        row.id_lapor +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan @endcan"
                                }
                            }
                        ],
                        'columnDefs': [{
                                "targets": 0,
                                "className": "text-center",
                            },
                            {
                                "targets": 4,
                                "className": "text-center",
                            },
                            {
                                "targets": 7,
                                "className": "text-center",
                            }
                        ]
                    });
                    if (end >= data.row_count) {
                        $('#ditinjau_next').hide();
                        $('#ditinjau_show_to').text(data.row_count);
                    } else {
                        $('#ditinjau_show_to').text(end);
                    }
                });
            });
</script>

{{-- Laporan Diperbaiki --}}
<script>
    var start = 0;
            var end = 50;

            $.ajax({
                'url': "laporan-data/diperbaiki/" + start + "/" + end,
                'method': "GET",
                'contentType': 'application/json',
                "beforeSend": showPreload,
                "complete": hidePreload,
            }).done(function(data) {
                $('#lap_diperbaiki').dataTable({
                    "bDestroy": true,
                    "ordering": false,
                    stateSave: true,
                    "bAutoWidth": false,
                    "data": data.diperbaiki,
                    "pageLength": 50,
                    "paging": false,
                    "info": false,
                    "columns": [{
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.nama_lengkap +
                                    "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                    row.nip +
                                    "'>" + row.nip +
                                    "</a> @can('list-all-perangkat-daerah') <br>" + row
                                    .perangkat_daerah +
                                    " @endcan"
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.jenis_diklat + "<br>" +
                                    row.sub_jenis_diklat
                            }
                        },
                        {
                            "data": "nama_diklat"
                        },
                        {
                            "data": "lama_pendidikan"
                        },
                        {
                            "render": function(data, type, row) {
                                return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return "<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='" +
                                    moment(row.edit_time).format('DD-MM-YYYY') +
                                    "'>Diperbaiki</span>"
                            }
                        },
                        {
                            "data": "alasan"
                        },
                        {
                            "render": function(data, type, row) {
                                return "@can('laporan-edit', 'laporan-delete') @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                    row.nip + "/" + row.id_lapor + "/" + row.status +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                    row.id_lapor +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan @endcan"
                            }
                        }
                    ],
                    'columnDefs': [{
                            "targets": 0,
                            "className": "text-center",
                        },
                        {
                            "targets": 4,
                            "className": "text-center",
                        },
                        {
                            "targets": 8,
                            "className": "text-center",
                        },
                    ],
                });
                $('#diperbaiki_count, #diperbaiki_total').text("[" + data.row_count + "]");
                if (data.row_count < 50) {
                    $('#diperbaiki_next').hide();
                    $('#diperbaiki_show_to').text(data.row_count);
                } else {
                    $('#diperbaiki_show_to').text(end);
                }
            });

            $('#diperbaiki_next, .page-item next').click(function() {
                start = start;
                end = end + 50;
                $.ajax({
                    'url': "laporan-data/diperbaiki/" + start + "/" + end,
                    'method': "GET",
                    'contentType': 'application/json',
                    "beforeSend": showPreload,
                    "complete": hidePreload,
                }).done(function(data) {
                    $('#lap_diperbaiki').dataTable({
                        "bDestroy": true,
                        "ordering": false,
                        stateSave: true,
                        "bAutoWidth": false,
                        "data": data.diperbaiki,
                        "pageLength": 50,
                        "paging": false,
                        "info": false,
                        "columns": [{
                                "render": function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.nama_lengkap +
                                        "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                        row.nip +
                                        "'>" + row.nip +
                                        "</a> @can('list-all-perangkat-daerah') <br>" +
                                        row
                                        .perangkat_daerah +
                                        " @endcan"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.jenis_diklat + "<br>" +
                                        row.sub_jenis_diklat
                                }
                            },
                            {
                                "data": "nama_diklat"
                            },
                            {
                                "data": "lama_pendidikan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='" +
                                        moment(row.edit_time).format('DD-MM-YYYY') +
                                        "'>Diperbaiki</span>"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "@can('laporan-edit', 'laporan-delete') @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                        row.nip + "/" + row.id_lapor + "/" +  row.status +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                        row.id_lapor +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan @endcan"
                                }
                            }
                        ],
                        'columnDefs': [{
                                "targets": 0,
                                "className": "text-center",
                            },
                            {
                                "targets": 4,
                                "className": "text-center",
                            },
                            {
                                "targets": 7,
                                "className": "text-center",
                            }
                        ]
                    });
                    if (end >= data.row_count) {
                        $('#diperbaiki_next').hide();
                        $('#diperbaiki_show_to').text(data.row_count);
                    } else {
                        $('#diperbaiki_show_to').text(end);
                    }
                });
            });
</script>

{{-- Laporan Disetujui --}}
<script>
    var start = 0;
            var end = 50;

            $.ajax({
                'url': "laporan-data/disetujui/" + start + "/" + end,
                'method': "GET",
                'contentType': 'application/json'
            }).done(function(data) {
                $('#lap_disetujui').dataTable({
                    "processing": true,
                    "bDestroy": true,
                    "ordering": false,
                    "bAutoWidth": false,
                    "data": data.disetujui,
                    "pageLength": 50,
                    "paging": false,
                    "info": false,
                    "columns": [{
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        }, {
                            "render": function(data, type, row) {
                                return row.nama_lengkap +
                                    "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                    row.nip +
                                    "'>" + row.nip +
                                    "</a> @can('list-all-perangkat-daerah') <br>" + row
                                    .perangkat_daerah +
                                    " @endcan"
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.jenis_diklat + "<br>" +
                                    row.sub_jenis_diklat
                            }
                        },
                        {
                            "data": "nama_diklat"
                        },
                        {
                            "data": "lama_pendidikan"
                        },
                        {
                            "render": function(data, type, row) {
                                return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return "<span class='badge badge-success' data-toggle='tooltip' data-placement='top' title='" +
                                    moment(row.edit_time).format('DD-MM-YYYY') + "'>Disetujui</span>"
                            }
                        },
                        {
                            "data": "alasan"
                        },
                        {
                            "render": function(data, type, row) {
                                return "@can('laporan-approve') @can('laporan-edit', 'laporan-delete') <td class='text-center'> @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                    row.nip + "/" + row.id_lapor + "/" + row.status +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                    row.id_lapor +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan <div> @endcan @endcan "
                            }
                        }
                    ],
                    'columnDefs': [{
                            "targets": 0,
                            "className": "text-center",
                        },
                        {
                            "targets": 4,
                            "className": "text-center",
                        },
                        {
                            "targets": 8,
                            "className": "text-center",
                        }
                    ]
                });

                $('#disetujui_count, #disetujui_total').text("[" + data.row_count + "]");
                if (data.row_count < 50) {
                    $('#disetujui_next').hide();
                    $('#disetujui_show_to').text(data.row_count);
                } else {
                    $('#disetujui_show_to').text(end);
                }

            });

            $('#disetujui_next').click(function() {
                start = start;
                end = end + 50;
                $.ajax({
                    'url': "laporan-data/disetujui/" + start + "/" + end,
                    'method': "GET",
                    'contentType': 'application/json'
                }).done(function(data) {
                    $('#lap_disetujui').dataTable({
                        "bDestroy": true,
                        "ordering": false,
                        stateSave: true,
                        "bAutoWidth": false,
                        "data": data.disetujui,
                        "pageLength": 50,
                        "paging": false,
                        "info": false,
                        "columns": [{
                                "render": function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            }, {
                                "render": function(data, type, row) {
                                    return row.nama_lengkap +
                                        "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                        row.nip +
                                        "'>" + row.nip +
                                        "</a> @can('list-all-perangkat-daerah') <br>" +
                                        row
                                        .perangkat_daerah +
                                        " @endcan"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.jenis_diklat + "<br>" +
                                        row.sub_jenis_diklat
                                }
                            },
                            {
                                "data": "nama_diklat"
                            },
                            {
                                "data": "lama_pendidikan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "<span class='badge badge-success' data-toggle='tooltip' data-placement='top' title='" +
                                        moment(row.edit_time).format('DD-MM-YYYY') +
                                        "'>Disetujui</span>"
                                }
                            },
                            {
                                "data": "alasan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return "@can('laporan-approve') @can('laporan-edit', 'laporan-delete') <td class='text-center'> @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                        row.nip + "/" + row.id_lapor + "/" + row.status +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                        row.id_lapor +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan <div> @endcan @endcan "
                                }
                            }
                        ],
                        'columnDefs': [{
                                "targets": 0,
                                "className": "text-center",
                            },
                            {
                                "targets": 4,
                                "className": "text-center",
                            },
                            {
                                "targets": 8,
                                "className": "text-center",
                            }
                        ],
                    });
                    if (end >= data.row_count) {
                        $('#disetujui_next').hide();
                        $('#disetujui_show_to').text(data.row_count);
                    } else {
                        $('#disetujui_show_to').text(end);
                    }
                });

            });
</script>

{{-- Laporan Ditolak --}}
<script>
    var start = 0;
            var end = 50;

            $.ajax({
                'url': "laporan-data/ditolak/" + start + "/" + end,
                'method': "GET",
                'contentType': 'application/json'
            }).done(function(data) {
                $('#lap_ditolak').dataTable({
                    "processing": true,
                    "bDestroy": true,
                    "ordering": false,
                    "bAutoWidth": false,
                    "data": data.ditolak,
                    "pageLength": 50,
                    "paging": false,
                    "info": false,
                    "columns": [{
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        }, {
                            "render": function(data, type, row) {
                                return row.nama_lengkap +
                                    "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                    row.nip +
                                    "'>" + row.nip +
                                    "</a> @can('list-all-perangkat-daerah') <br>" + row
                                    .perangkat_daerah +
                                    " @endcan"
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return row.jenis_diklat + "<br>" +
                                    row.sub_jenis_diklat
                            }
                        },
                        {
                            "data": "nama_diklat"
                        },
                        {
                            "data": "lama_pendidikan"
                        },
                        {
                            "render": function(data, type, row) {
                                return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                            }
                        },
                        {
                            "render": function(data, type, row) {
                                return "<span class='badge badge-danger' data-toggle='tooltip' data-placement='top' title='" +
                                    moment(row.edit_time).format('DD-MM-YYYY') + "'>Ditolak</span>"
                            }
                        },
                        {
                            "data": "alasan"
                        },
                        {
                            "render": function(data, type, row) {
                                return "@can('laporan-approve') @can('laporan-edit', 'laporan-delete') <td class='text-center'> @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                    row.nip + "/" + row.id_lapor + "/" + row.status +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                    row.id_lapor +
                                    "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan <div> @endcan @endcan"
                            }
                        }
                    ],
                    'columnDefs': [{
                            "targets": 0,
                            "className": "text-center",
                        },
                        {
                            "targets": 4,
                            "className": "text-center",
                        },
                        {
                            "targets": 8,
                            "className": "text-center",
                        }
                    ]
                });

                $('#ditolak_count, #ditolak_total').text("[" + data.row_count + "]");
                if (data.row_count < 50) {
                    $('#ditolak_next').hide();
                    $('#ditolak_show_to').text(data.row_count);
                } else {
                    $('#ditolak_show_to').text(end);
                }

            });

            $('#ditolak_next').click(function() {
                start = start;
                end = end + 50;
                $.ajax({
                    'url': "laporan-data/ditolak/" + start + "/" + end,
                    'method': "GET",
                    'contentType': 'application/json'
                }).done(function(data) {
                    $('#lap_ditolak').dataTable({
                        "bDestroy": true,
                        "ordering": false,
                        stateSave: true,
                        "bAutoWidth": false,
                        "data": data.ditolak,
                        "pageLength": 50,
                        "paging": false,
                        "info": false,
                        "columns": [{
                                "render": function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
                            }, {
                                "render": function(data, type, row) {
                                    return row.nama_lengkap +
                                        "<br><a class='modal-nip' href='javascript:void(0)' data-nip='" +
                                        row.nip +
                                        "'>" + row.nip +
                                        "</a> @can('list-all-perangkat-daerah') <br>" +
                                        row
                                        .perangkat_daerah +
                                        " @endcan"
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return row.jenis_diklat + "<br>" +
                                        row.sub_jenis_diklat
                                }
                            },
                            {
                                "data": "nama_diklat"
                            },
                            {
                                "data": "lama_pendidikan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return moment(row.tgl_sttpp).format('DD-MM-YYYY')
                                }
                            },
                            {
                                "render": function(data, type, row) {
                                    return "<span class='badge badge-danger' data-toggle='tooltip' data-placement='top' title='" +
                                        moment(row.edit_time).format('DD-MM-YYYY') +
                                        "'>Ditolak</span>"
                                }
                            },
                            {
                                "data": "alasan"
                            },
                            {
                                "render": function(data, type, row) {
                                    return "@can('laporan-approve') @can('laporan-edit', 'laporan-delete') <td class='text-center'> @can('laporan-edit') <a href = '{{ url('/laporan/edit/') }}/" +
                                        row.nip + "/" + row.id_lapor + "/" + row.status +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Edit Laporan'> <i class = 'fa-regular fa-pen-to-square'> </i></a> @endcan @can('laporan-delete') <a href='javascript:void(0)' class='icon-delete' data-id-delete='" +
                                        row.id_lapor +
                                        "' data-toggle = 'tooltip' data-placement = 'top' title = 'Hapus Laporan'> <i class = 'fa-solid fa-trash-can text-danger'> </i></a> @endcan <div> @endcan @endcan"
                                }
                            }
                        ],
                        'columnDefs': [{
                                "targets": 0,
                                "className": "text-center",
                            },
                            {
                                "targets": 4,
                                "className": "text-center",
                            },
                            {
                                "targets": 8,
                                "className": "text-center",
                            }
                        ]
                    });
                    if (end >= data.row_count) {
                        $('#ditolak_next').hide();
                        $('#ditolak_show_to').text(data.row_count);
                    } else {
                        $('#ditolak_show_to').text(end);
                    }
                });
            });
</script>
@endpush
@endif

@push('js')
<script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
@endpush

@push('css')
<style>
    #btn-filter:hover {
        color: #fff !important;
    }

    .badge.badge-outlined {
        border: 2px solid #FFD600;
        background-color: transparent;
        color: #FFD600;
    }
</style>
@endpush