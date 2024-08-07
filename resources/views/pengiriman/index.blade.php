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

                <div class="card">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>Daftar Pengiriman Pengembangan Kompetensi</h4>
                        </div>
                        <div class="p-2 bd-highlight ">
                            @can('export-excel')
                            <a href="{{ route('pengiriman.cetak') }}" class="btn btn-primary me-5"><i
                                    class="fa-regular fa-file-excel fa-beat"></i>
                                &nbsp;
                                Export Excel</a>
                            @endcan
                            @can('pengiriman-create')
                            <a href="{{ route('pengiriman.redirect') }}" style="margin-left: auto"
                                class="btn btn-primary"> <i class="fa-solid fa-circle-plus fa-beat"></i> &nbsp; Tambah
                                Pengiriman</a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="dilaksanakan-tab" data-toggle="tab"
                                    href="#dilaksanakan" role="tab" aria-controls="dilaksanakan"
                                    aria-selected="true"><b>Pengiriman
                                        <sup id="dilaksanakan_count"></sup></b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab"
                                    aria-controls="selesai" aria-selected="false"><b>Selesai
                                        <sup id="selesai_count"></sup></b></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="dilaksanakan" role="tabpanel"
                                aria-labelledby="dilaksanakan-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2" id="pengiriman_dilaksanakan">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th>Penyelenggara</th>
                                                <th class="text-center">SPT</th>
                                                <th class="text-center">Status</th>
                                                @can('pengiriman-send')
                                                <th class="text-center" width="3%">Laporan</th>
                                                @endcan
                                                @can('pengiriman-edit', 'pengiriman-delete')
                                                <th class="text-center">Admin</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($dilaksanakan as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_lengkap }} <br> NIP. <a class="modal-nip"
                                                        href="javascript:void(0)" data-nip="{{ $item->nip }}">{{
                                                        $item->nip }}</a></td>
                                                <td>{{ $item->jenis_diklat }} <br> {{ $item->sub_jenis_diklat }}
                                                </td>
                                                <td>{{ $item->nama_diklat }}</td>
                                                <td>{{ $item->penyelenggara_diklat }} <br>
                                                    {{ $item->tempat_diklat }}</td>
                                                <td class="text-center">
                                                    {{ Carbon\Carbon::parse($item->tgl_surat)->format('d-m-Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->stt == '0')
                                                    <span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Dikirim
                                                    </span>
                                                    @else
                                                    <span class="badge badge-dark" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Selesai</span>
                                                    @endif
                                                </td>
                                                @can('pengiriman-send')
                                                <td class="text-center">
                                                    @if ($item->stt == '0')
                                                    <a href="{{ route('laporan.create', [$item->nip, $item->id_usul]) }}"
                                                        class="icon-edit" data-toggle="tooltip" data-placement="top"
                                                        title="" data-original-title="Laporan">
                                                        <i class="fa-solid fa-graduation-cap"></i></a>
                                                    @else
                                                    <a class="icon-edit" data-toggle="tooltip" data-placement="top"
                                                        title="" data-original-title="Laporan">
                                                        <i class="fa-solid fa-graduation-cap"></i></a>
                                                    @endif
                                                </td>
                                                @endcan
                                                @can('pengiriman-edit', 'pengiriman-delete')
                                                <td class="text-center">
                                                    @can('pengiriman-edit')
                                                    <a href="{{ route('pengiriman.edit', [$item->nip, $item->id_pengiriman]) }}"
                                                        class="icon-edit" data-toggle="tooltip" data-placement="top"
                                                        title="" data-original-title="Edit Pengiriman">
                                                        <i class="fa-regular fa-pen-to-square"></i></a>
                                                    @endcan
                                                    @can('pengiriman-delete')
                                                    <a class="icon-delete" href="javascript:void(0)"
                                                        data-id-delete="{{ $item->id_pengiriman }}"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Hapus pengiriman">
                                                        <i class="fa-solid fa-trash-can"></i></a>
                                                    @endcan
                                                </td>
                                                @endcan
                                            </tr>
                                            @endforeach --}}
                                        </tbody>
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
                            <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-2" id="pengiriman_selesai">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Jenis Diklat</th>
                                                <th>Nama Diklat</th>
                                                <th>Penyelenggara</th>
                                                <th class="text-center">SPT</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($selesai as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_lengkap }} <br> NIP. <a class="modal-nip"
                                                        href="javascript:void(0)" data-nip="{{ $item->nip }}">{{
                                                        $item->nip }}</a>
                                                </td>
                                                <td>{{ $item->jenis_diklat }} <br> {{ $item->sub_jenis_diklat }}
                                                </td>
                                                <td>{{ $item->nama_diklat }}</td>
                                                <td>{{ $item->penyelenggara_diklat }} <br>
                                                    {{ $item->tempat_diklat }}</td>
                                                <td class="text-center">
                                                    {{ Carbon\Carbon::parse($item->tgl_surat)->format('d-m-Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->stt == '0')
                                                    <span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Dikirim
                                                    </span>
                                                    @else
                                                    <span class="badge badge-dark" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="{{ Carbon\Carbon::parse($item->edit_time)->format('d-m-Y ; H:i') }} WIB">
                                                        Selesai</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex">
                                    <div class="p-2 flex-grow-1">Showing <span>1</span> to <span
                                            id="selesai_show_to"></span> of
                                        <span id="selesai_total"></span>
                                    </div>
                                    <div class="p-2">
                                        <div class="paginate_button page-item next">
                                            <button id="selesai_next" class="page-link">Next</button>
                                        </div>
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
    Swal.fire('Berhasil', "{{ session('success') }}", 'success');
</script>
@endif

@if (session('error'))
@include('partials.error_alert')
@endif


@include('partials.modal_detail_asn')
@include('partials.modal_delete')
@include('partials.check_session')
<script>
    $(document).ready(function() {
            $('.icon-delete').click(function() {
                $('#deletemodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });
                var idPengiriman = $(this).attr('data-id-delete');
                var actionDel = "{{ url('/pengiriman/destroy') }}" + "/" + idPengiriman;
                $("#form-delete").attr("action", actionDel);
                $("#text-item-delete").text("Pengiriman Bang Kom");
            });
        });
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
                    url: "{{ route('pengiriman.index') }}",
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

        function renderPengiriman(data, type, row) {
            var status = row.status;
            var html = '<td class="text-center">';

            if (status == '0') {
                html += `<a href="{{ url('/pengiriman/create/') }}/` + row.nip + `/` + row.id_usul + `" class="icon-edit" data-toggle="tooltip" data-placement="top" title="" data-original-title="Laporan">`;
            } else {
                html += '<a class="icon-edit" data-toggle="tooltip" data-placement="top" title="" data-original-title="Laporan">';
            }

            html += '<i class="fa-solid fa-graduation-cap"></i></a></td>';

            return html;
        }

        function renderActions(data, type, row) {
            return `
        @can('pengiriman-edit', 'pengiriman-delete')
        <td class="text-center">
            @can('pengiriman-edit')
            <a href="{{ url('/pengiriman/edit/') }}/${row.nip}/${row.id_pengiriman}"
                class="icon-edit" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="Edit Pengiriman">
                <i class="fa-regular fa-pen-to-square"></i></a>
            @endcan
            @can('pengiriman-delete')
            <a class="icon-delete" href="javascript:void(0)"
                data-toggle="tooltip" data-placement="top" onclick="confirmDelete('${row.id_pengiriman}')"
                data-original-title="Hapus pengiriman">
                <i class="fa-solid fa-trash-can"></i></a>
            @endcan
        </td>
        @endcan
        `;
        }

        // Function to initialize DataTable Pengiriman Dilaksanakan (DIKIRIM)
        function initializeDataTableDilaksanakan(data) {
            $('#pengiriman_dilaksanakan').dataTable({
                "bDestroy": true,
                "ordering": false,
                stateSave: true,
                "bAutoWidth": false,
                "data": data.data,
                "pageLength": 50,
                "paging": false,
                "info": false,
                "columns": [{
                        "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        "render": (data, type, row) =>
                            `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>`
                    },
                    {
                        "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}`
                    },
                    {
                        "data": "nama_diklat"
                    },
                    {
                        "render": (data, type, row) => `${row.penyelenggara_diklat}<br>${row.tempat_diklat}`
                    },
                    {
                        "render": (data, type, row) => `${moment(row.tgl_surat).format('DD-MM-YYYY')}`
                    },
                    {
                        "render": (data, type, row) => `<span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="${moment(row.entry_time).format('DD-MM-YYY h:s')} WIB">
                                                        Dikirim
                                                    </span>`
                    },
                    {
                        "render": (data, type, row) => renderPengiriman(data, type, row)
                    },
                    {
                        "render": (data, type, row) => renderActions(data, type, row)
                    },
                ],
                'columnDefs': [{
                    "targets": [0, 7],
                    "className": "text-center"
                }]
            });
        }

        function fetchDataPengirimanDilaksanakan() {
            var start = 0;
            $.ajax({
                'url': `pengiriman/data/dilaksanakan/${start}/${end}`,
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

        // Function to initialize DataTable Pengiriman Dilaksanakan (DIKIRIM)
        function initializeDataTableSelesai(data) {
            $('#pengiriman_selesai').dataTable({
                "bDestroy": true,
                "ordering": false,
                stateSave: true,
                "bAutoWidth": false,
                "data": data.data,
                "pageLength": 50,
                "paging": false,
                "info": false,
                "columns": [{
                        "render": (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        "render": (data, type, row) =>
                            `${row.nama_lengkap}<br><a class='modal-nip' href='javascript:void(0)' data-nip='${row.nip}'>${row.nip}</a>`
                    },
                    {
                        "render": (data, type, row) => `${row.jenis_diklat}<br>${row.sub_jenis_diklat}`
                    },
                    {
                        "data": "nama_diklat"
                    },
                    {
                        "render": (data, type, row) => `${row.penyelenggara_diklat}<br>${row.tempat_diklat}`
                    },
                    {
                        "render": (data, type, row) => `${moment(row.tgl_surat).format('DD-MM-YYYY')}`
                    },
                    {
                        "render": (data, type, row) => `<span class="badge badge-success" data-toggle="tooltip"
                                                        data-placement="top" title=""
                                                        data-original-title="${moment(row.entry_time).format('DD-MM-YYY h:s')} WIB">
                                                        Dikirim
                                                    </span>`
                    },
                ],
                'columnDefs': [{
                    "targets": [5,6],
                    "className": "text-center"
                }]
            });
        }

        function fetchDataPengirimanSelesai() {
            var start = 0;
            $.ajax({
                'url': `pengiriman/data/selesai/${start}/${end}`,
                'method': "GET",
                'contentType': 'application/json',
                "beforeSend": showPreload,
                "complete": hidePreload,
            }).done(function(data) {
                initializeDataTableSelesai(data);
                $('#selesai_count, #selesai_total').text(`[${data.row_count}]`);
                if (data.row_count < 50) {
                    $('#selesai_next').hide();
                    $('#selesai_show_to').text(data.row_count);
                } else {
                    $('#selesai_next').show();
                    $('#selesai_show_to').text(end);
                }
            });
        }

        $('#dilaksanakan_next, #disetujui_next').on('click', function() {
            end += 50; // Increment 'end' by 50
            fetchAllData();
        });

        fetchAllData();

        function fetchAllData() {
            fetchDataPengirimanDilaksanakan();
            fetchDataPengirimanSelesai();
            filterCheck();
        }
</script>
{{-- DELETE DATA --}}
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    function confirmDelete(idPengiriman) {
        $(document).ready(function() {
            $('#deletemodal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });

            var actionDel = "{{ url('/pengiriman/destroy/') }}" + "/" + idPengiriman;
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
                Swal.fire('Sukses', response.message, response.status);
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
                Swal.fire('Error', errorText, response.status);
            }
        });
    });
</script>
@endpush
