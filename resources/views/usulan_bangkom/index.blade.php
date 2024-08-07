@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

@push('css')
<style>
    .icon-edit {
        font-size: 1rem;
        margin-right: 4px;
    }

    .icon-delete {
        color: red;
        font-size: 1rem;
    }
</style>
@endpush

<div class="section-body">

    @can('usulan-search-option')
    <div class="section-body">
        <div class="card">
            <form action="{{ route('usulan_bangkom') }}" method="get">
                <div class="card-body">
                    <div class="row">
                        <label for="perangkat_daerah" class="col-sm-2 col-form-label my-auto text-dark">Perangkat
                            Daerah</label>
                        <div class="
                                                @can('usulan-filter-option')
                                                    col-md-8
                                                @endcan

                                                @cannot('usulan-filter-option')
                                                    col-md-9
                                                @endcannot
                                            ">
                            <select class="form-control select2" id="param" name="id_perangkat_daerah" required>
                                <option value="">..Perangkat Daerah..</option>
                                @foreach (getPerangkatDaerah()->Data as $item)
                                <option value="{{ $item->id_perangkat_daerah }}" {{ $item->id_perangkat_daerah ==
                                    session()->get('id_perangkat_daerah') ? 'selected' : '' }}>
                                    {{ $item->id_perangkat_daerah }} . {{ $item->perangkat_daerah }}
                                </option>
                                @endforeach
                                <option value="all" {{ session()->get('id_perangkat_daerah') == 'all' ? 'selected' : ''
                                    }}>
                                    00. Semua Perangkat Daerah</option>
                            </select>
                        </div>

                        <div class="btn-group
                                                @can('usulan-filter-option')
                                                    col-md-2
                                                @endcan

                                                @cannot('usulan-filter-option')
                                                    col-md-1
                                                @endcannot
                                            " role="group" aria-label="Basic checkbox toggle button group">
                            @can('usulan-filter-option')
                            <a href="javascript:void(0)"
                                class="btn btn-outline-primary pt-2 @if (session()->has('filter_nama_diklat') || session()->has('filter_tanggal')) text-warning @endif"
                                id="btn-filter"><i class="fa-solid fa-filter"></i>
                                Filter
                            </a>
                            @endcan

                            <button type="submit" id="button-cari" class="btn btn-primary pt-2">Cari</button>
                        </div>

                    </div>
                    @can('usulan-filter-option')
                    <div class="d-none" id="filter-usulan">
                        <div class="row">
                            <label for="perangkat_daerah" class="col-sm-2 col-form-label my-auto text-dark mt-2">Nama
                                atau NIP</label>
                            <div class="col-md-5">
                                <div class="input-group mt-2">
                                    <input type="search" name="filter_nama_nip" class="form-control" autocomplete="off"
                                        value="{{ session()->get('filter_nama_nip') }}" placeholder="..Nama atau NIP..">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group mt-2">
                                    <input type="search" name="filter_nama_diklat" class="form-control"
                                        autocomplete="off" value="{{ session()->get('filter_nama_diklat') }}"
                                        placeholder="..Nama Diklat..">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan

                    @push('js')
                    <script>
                        $('#btn-filter').click(function() {
                                        if ($('#filter-usulan').hasClass('d-none')) {
                                            $('#filter-usulan').fadeIn();
                                            $('#filter-usulan').slideDown();
                                            $('#filter-usulan').removeClass('d-none');
                                            $('#btn-filter').html('<i class="fa-solid fa-times"></i> Tutup');
                                        } else {
                                            $('#filter-usulan').fadeOut();
                                            $('#filter-usulan').slideUp();
                                            $('#filter-usulan').addClass('d-none');
                                            $('#btn-filter').html('<i class="fa-solid fa-filter"></i> Filter');
                                        }
                                    });
                    </script>
                    @endpush

                </div>
            </form>
        </div>
    </div>
    @endcan

    <section>
        <div class="row">
            <div class="col-12">
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


                <div id="table-data"
                    class="card {{ (auth()->user()->cannot('usulan-search-option') || session()->get('id_perangkat_daerah') != null ) ? '' : 'd-none' }}">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>Daftar Usulan Pengembangan Kompetensi</h4>
                        </div>
                        @can('export-excel')
                        <a href="{{ route('usulan.cetak') }}" class="btn btn-primary me-5"><i
                                class="fa-regular fa-file-excel fa-beat"></i>
                            &nbsp;
                            Export Excel</a>
                        @endcan

                        <div class="p-2 bd-highlight ">
                            @can('usulan-create')
                            <a href="{{ route('usulan.redirect') }}" style="margin-left: auto"
                                class="btn btn-primary"><i class="fa-solid fa-circle-plus fa-beat"></i> &nbsp;
                                Tambah
                                Usulan</a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ditinjau-tab" data-toggle="tab" href="#ditinjau"
                                    role="tab" aria-controls="ditinjau" aria-selected="true"><b>Ditinjau
                                        <sup id="ditinjau_count"></sup></b>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="disetujui-tab" data-toggle="tab" href="#disetujui" role="tab"
                                    aria-controls="disetujui" aria-selected="false"><b>Disetujui
                                        <sup id="disetujui_count"></sup></b>

                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="dilaksanakan-tab" data-toggle="tab" href="#dilaksanakan"
                                    role="tab" aria-controls="dilaksanakan" aria-selected="false"><b>Dikirim
                                        <sup id="dilaksanakan_count"></sup></b>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ditolak-tab" data-toggle="tab" href="#ditolak" role="tab"
                                    aria-controls="ditolak" aria-selected="false"><b>Ditolak
                                        <sup id="ditolak_count"></sup></b>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="ditinjau" role="tabpanel"
                                aria-labelledby="ditinjau-tab">
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="usul_ditinjau">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Diklat</th>
                                                    <th>Nama Diklat</th>
                                                    <th>Dasar</th>
                                                    <th class="text-center">Usulan</th>
                                                    <th class="text-center">Status</th>
                                                    @can('usulan-edit', 'usulan-delete')
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
                            </div>
                            <div class="tab-pane fade" id="disetujui" role="tabpanel" aria-labelledby="disetujui-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2" id="usul_disetujui">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th class="text-center">Nama Diklat</th>
                                                <th class="text-center">Dasar</th>
                                                <th class="text-center">Usulan</th>
                                                <th class="text-center">Status</th>
                                                @can('usulan-send')
                                                <th class="text-center">Pengiriman</th>
                                                @endcan
                                                @can('usulan-edit', 'usulan-delete')
                                                <th class="text-center">Admin</th>
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
                            <div class="tab-pane fade" id="dilaksanakan" role="tabpanel"
                                aria-labelledby="dilaksanakan-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2" id="usul_dilaksanakan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th class="text-center">Nama Diklat</th>
                                                <th class="text-center">Dasar</th>
                                                <th class="text-center">Usulan</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                            id="dilaksanakan_show_to"></span> of
                                        <span id="dilaksanakan_total"></span>
                                    </div>
                                    <div class="p-2">
                                        <div class="paginate_button page-item next">
                                            <button id="dilaksanakan_next" class="page-link">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2" id="usul_ditolak">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                {{-- <th>Sub Jenis Diklat</th> --}}
                                                <th class="text-center">Nama Diklat</th>
                                                <th class="text-center">Dasar</th>
                                                <th class="text-center">Usulan</th>
                                                <th class="text-center">Status</th>
                                                <th>Alasan</th>
                                                @can('usulan-edit', 'usulan-delete')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('js')
@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Sukses', "{{ session('success') }}", 'success');
</script>
@endif

@if (session('error'))
@include('partials.error_alert')
@endif

@include('partials.modal_detail_asn')
@include('partials.modal_delete')
@include('partials.check_session')
@endpush

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

<script>
    var end = 50;

    function renderUsulan(data, type, row) {
        switch (row.dasar_usulan) {
            case "Human Capital Development Plan (HCDP)":
                return "HCDP";
            case "Standar Kompetensi Jabatan (SKJ)":
                return "SKJ";
            case "Analisis Kebutuhan Diklat (AKD)":
                return "AKD";
            case "Penawaran":
                return "Penawaran";
            default:
                return "";
        }
    }

    function renderEntryTime(data, type, row) {
        return `
            <span data-toggle="tooltip" title="${moment(row.entry_time).format('H:m:s')} WIB">
                ${moment(row.entry_time).format('DD-MM-YYYY')}
            </span>`;   
    }


    function renderActions(data, type, row) {
        return `
            @can('usulan-edit', 'usulan-delete')
            <td class="text-center">
                @can('usulan-edit')
                <a href="{{ url('/update-status/') }}/${row.nip}/${row.id_usul}"
                    class="icon-edit" data-toggle="tooltip" data-placement="top"
                    title="Edit Usulan">
                    <i class="fa-regular fa-pen-to-square"></i></a>
                @endcan
                @can('usulan-delete')
                <a class="icon-delete" href="javascript:void(0)"
                    data-id-delete="${row.id_usul}" data-toggle="tooltip"
                    data-placement="top" title="Hapus Usulan"
                    onclick="confirmDelete(${row.id_usul})">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
                @endcan
            </td>
            @endcan`;
    }

    function renderPengiriman(data, type, row) {
        return `
        @can('usulan-send')
        <td class="text-center">
            <a href="{{ url('/pengiriman/create/') }}/${row.nip}/${row.id_usul}"
                class="icon-edit" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="Pengiriman">
                <i class="fa-regular fa-paper-plane"></i></a>
        </td>
        @endcan    
        `;
    }

    // Function to initialize DataTable usulan DITINJAU
    function initializeDataTableDitinjau(data) {
        $('#usul_ditinjau').dataTable({
            "bDestroy": true,
            "ordering": false,
            stateSave: true,
            "bAutoWidth": false,
            "data": data.data,
            "pageLength": 50,
            "paging": false,
            "info": false,
            "columns": [
                { "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { "render": (data, type, row) => `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>` },
                { "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}` },
                { "data": "nama_diklat" },
                { "render": renderUsulan },
                { "render": renderEntryTime },
                { "render": (data, type, row) => `<span class='badge badge-warning' data-toggle='tooltip' data-placement='top' title='${moment(row.entry_time).format('DD-MM-YYYY')}'>Ditinjau</span>` },
                { "render": renderActions }
            ],
            'columnDefs': [
                { "targets": [0, 4, 7], "className": "text-center" }
            ]
        });
    }

    function fetchDataUsulanDitinjau() {
        var start = 0;
        $.ajax({
            'url': `usulan/data/ditinjau/${start}/${end}`,
            'method': "GET",
            'contentType': 'application/json',
            "beforeSend": showPreload,
            "complete": hidePreload,
        }).done(function(data) {
            initializeDataTableDitinjau(data);
            $('#ditinjau_count, #ditinjau_total').text(`[${data.row_count}]`);
            if (data.row_count < 50) {
                $('#ditinjau_next').hide();
                $('#ditinjau_show_to').text(data.row_count);
            } else {
                $('#ditinjau_next').show();
                $('#ditinjau_show_to').text(end);
            }
        });
    }

    // Function to initialize DataTable usulan DISETUJUI
    function initializeDataTableDisetujui(data) {
        $('#usul_disetujui').dataTable({
            "bDestroy": true,
            "ordering": false,
            stateSave: true,
            "bAutoWidth": false,
            "data": data.data,
            "pageLength": 50,
            "paging": false,
            "info": false,
            "columns": [
                { "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { "render": (data, type, row) => `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>` },
                { "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}` },
                { "data": "nama_diklat" },
                { "render": renderUsulan },
                { "render": renderEntryTime },
                { "render": (data, type, row) => `<span class='badge badge-success' data-toggle='tooltip' data-placement='top' title='${moment(row.entry_time).format('DD-MM-YYYY')}'>Disetujui</span>` },
                { "render": renderPengiriman },
                { "render": renderActions }
            ],
            'columnDefs': [
                { "targets": [0, 6, 7], "className": "text-center" }
            ]
        });
    }

    function fetchDataUsulanDisetujui() {
        var start = 0;
        $.ajax({
            'url': `usulan/data/disetujui/${start}/${end}`,
            'method': "GET",
            'contentType': 'application/json',
            "beforeSend": showPreload,
            "complete": hidePreload,
        }).done(function(data) {
            initializeDataTableDisetujui(data);
            $('#disetujui_count, #disetujui_total').text(`[${data.row_count}]`);
            if (data.row_count < 50) {
                $('#disetujui_next').hide();
                $('#disetujui_show_to').text(data.row_count);
            } else {
                $('#disetujui_next').show();
                $('#disetujui_show_to').text(end);
            }
        });
    }

    // Function to initialize DataTable usulan DILAKSANAKAN
    function initializeDataTableDilaksanakan(data) {
        $('#usul_dilaksanakan').dataTable({
            "bDestroy": true,
            "ordering": false,
            stateSave: true,
            "bAutoWidth": false,
            "data": data.data,
            "pageLength": 50,
            "paging": false,
            "info": false,
            "columns": [
                { "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { "render": (data, type, row) => `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>` },
                { "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}` },
                { "data": "nama_diklat" },
                { "render": renderUsulan },
                { "render": renderEntryTime },
                { "render": (data, type, row) => `<span class='badge badge-dark' data-toggle='tooltip' data-placement='top' title='${moment(row.entry_time).format('DD-MM-YYYY')}'>Dikirim</span>` },
            ],
            'columnDefs': [
                { "targets": [4,6], "className": "text-center" }
            ]
        });
    }

    function fetchDataUsulanDilaksanakan() {
        var start = 0;
        $.ajax({
            'url': `usulan/data/dilaksanakan/${start}/${end}`,
            'method': "GET",
            'contentType': 'application/json',
            "beforeSend": showPreload,
            "complete": hidePreload,
        }).done(function(data) {
            initializeDataTableDilaksanakan(data);
            $('#dilaksanakan_count, #dilaksanakan_total').text(`[${data.row_count}]`);
            if (data.row_count < 50) {
                $('#dilaksanakan_next').hide();
                $('#dilaksanakan_show_to').text(data.row_count);
            } else {
                $('#dilaksanakan_next').show();
                $('#dilaksanakan_show_to').text(end);
            }
        });
    }

    // Function to initialize DataTable usulan DITOLAK
    function initializeDataTableDitolak(data) {
        $('#usul_ditolak').dataTable({
            "bDestroy": true,
            "ordering": false,
            stateSave: true,
            "bAutoWidth": false,
            "data": data.data,
            "pageLength": 50,
            "paging": false,
            "info": false,
            "columns": [
                { "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { "render": (data, type, row) => `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>` },
                { "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}` },
                { "data": "nama_diklat" },
                { "render": renderUsulan },
                { "render": renderEntryTime },
                { "render": (data, type, row) => `<span class='badge badge-danger' data-toggle='tooltip' data-placement='top' title='${moment(row.entry_time).format('DD-MM-YYYY')}'>Ditolak</span>` },
                { "data": "alasan"},
                { "render": renderActions },
            ],
            'columnDefs': [
                { "targets": [4,6], "className": "text-center" }
            ]
        });
    }

    function fetchDataUsulanDitolak() {
        var start = 0;
        $.ajax({
            'url': `usulan/data/ditolak/${start}/${end}`,
            'method': "GET",
            'contentType': 'application/json',
            "beforeSend": showPreload,
            "complete": hidePreload,
        }).done(function(data) {
            initializeDataTableDitolak(data);
            $('#ditolak_count, #ditolak_total').text(`[${data.row_count}]`);
            if (data.row_count < 50) {
                $('#ditolak_next').hide();
                $('#ditolak_show_to').text(data.row_count);
            } else {
                $('#ditolak_next').show();
                $('#ditolak_show_to').text(end);
            }
        });
    }

    $('#ditinjau_next, #disetujui_next').on('click', function() {
        end += 50; // Increment 'end' by 50
        fetchAllData();
    });

    fetchAllData();

    function fetchAllData() {
        fetchDataUsulanDitinjau();
        fetchDataUsulanDisetujui();
        fetchDataUsulanDilaksanakan();
        fetchDataUsulanDitolak();
        filterCheck();
    }
</script>

<script>
    $(document).ready(function() {
        // Intercept form submit
        $("#button-cari").click(function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Serialize form data
            var formData = $("form").serialize();

            // Send AJAX request
            $.ajax({
                url: "{{ route('usulan_bangkom') }}",
                type: "get",
                data: formData,
                "beforeSend": showPreload,
                "complete": hidePreload,
                success: function(response) {
                    fetchAllData();
                    $('#loading').modal('hide');
                    $('#table-data').removeClass('d-none');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>

{{-- DELETE DATA --}}
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    function confirmDelete(idUsul) {
        $(document).ready(function() {
            $('#deletemodal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });

            var actionDel = "{{ url('usulan-bangkom/destroy') }}" + "/" + idUsul;
            $("#form-delete").attr("action", actionDel);
            $("#text-item-delete").text("Usulan Bang Kom");
        });
    };

    $(document).on('submit', '#form-delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                $('#deletemodal').modal('hide');
                Swal.fire('Sukses', response.message, 'success');
                setTimeout(function() {
                    Swal.close();
                }, 1000);
                fetchAllData();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorText = '';
                $.each(errors, function(key, value) {
                    errorText += value + '<br>';
                });
                Swal.fire('Error', errorText, 'error');
            }
        });
    });
</script>

@endpush

@push('js')
<script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
@endpush