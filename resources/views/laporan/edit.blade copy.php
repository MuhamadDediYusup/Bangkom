@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

@if (!empty($data->file_surat_laporan))
@push('css')
<style class="file-spt-pdfview">
    .custom-input-pdf {
        width: 90%;
        float: right;
    }

    .custom-input-pdf input:hover {
        cursor: pointer;
    }

    .custom-icon-pdf {
        display: inline-block;
        width: 5%;
        height: calc(2.25rem + 6px);
        float: left;
        padding-top: 7px;
        padding-left: 12px;
    }

    .style-icon-pdf {
        font-size: 22px;
        color: #ff8c00;
    }
</style>
@endpush
@endif

@push('css')
<style>
    .custom-input-pdf2 {
        width: 90%;
        float: right;
    }

    .custom-input-pdf2 input:hover {
        cursor: pointer;
    }

    .custom-icon-pdf2 {
        display: inline-block;
        width: 5%;
        height: calc(2.25rem + 6px);
        float: left;
        padding-top: 7px;
        padding-left: 12px;
    }

    .style-icon-pdf2 {
        font-size: 22px;
        color: #ff8c00;
    }
</style>
@endpush

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan edit pelaporan pengembangan kompetensi.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                    <form action="{{ route('laporan.update', [$data->nip_pegawai, $data->id_lapor]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $data->id_diklat }}" name="id_diklat">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="{{ $pegawai->nama_lengkap }}" class="form-control" readonly>
                                    <input type="hidden" name="id_usul" value="@if (!empty($data->id_usul)) {{ $data->id_usul }} @endif">
                                    <input type="hidden" name="id_pengiriman" value="@if (!empty($data->id_lapor)) {{ $data->id_lapor }} @endif">
                                    <input type="hidden" name="referer_url" value="{{ $url_referer }}">
                                    <input type="hidden" name="param_url" value="{{ $param }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip" value="{{ $pegawai->nip }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <input type="search" name="jabatan" value="{{ $pegawai->jabatan }}" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="">..Jenis Diklat..</option>
                                        @foreach ($jenis_diklat as $item)
                                        <option value="{{ $item->jenis_diklat }}" {{ $item->jenis_diklat == $data->jenis_diklat ? 'selected' : '' }}>
                                            {{ $item->jenis_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sub Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="sub_jenis_diklat" id="sub_jenis_diklat" required>
                                        <option value="">..Sub Jenis Diklat..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Rumpun Diklat <span class="text-danger">*</span>&nbsp; <a href="javascript:void(0)" class="show-pdf pdf-rumpun-diklat badge badge-pill badge-info"><i class="fa-solid fa-info"></i>
                                        </a></label>
                                    <select class="form-control select2" name="rumpun_diklat" id="rumpun_diklat" required>
                                        <option value="">..Rumpun Diklat..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat <span class="text-danger">*</span></label>
                                    <input type="search" name="nama_diklat" maxlength="250" value="{{ strip_tags($data->nama_diklat) }}" placeholder="..Nama Diklat.." class="form-control @error('nama_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat <span class="text-danger">*</span></label>
                                    <input type="search" name="tempat_diklat" value="{{ $data->tempat_diklat }}" placeholder="..Tempat Bang Kom.." class="form-control @error('tempat_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penyelenggara <span class="text-danger">*</span></label>
                                    <input type="search" name="penyelenggara_diklat" value="{{ $data->penyelenggara_diklat }}" placeholder="..Penyelenggara Bang Kom.." class="form-control @error('penyelenggara_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_mulai" value="{{ $data->tgl_mulai }}" placeholder="..Tangga Mulai.." class="form-control @error('tgl_mulai')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selesai <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_selesai" value="{{ $data->tgl_selesai }}" placeholder="..Tanggal Selesai.." class="form-control @error('tahun_selesai')
                                            is-invalid
                                        @enderror" required>
                                    @error('tahun_selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor SPT</label>
                                    <input type="text" name="nomor_surat" value="{{ $data->nomor_surat }}" placeholder="..Nomor SPT.." class="form-control @error('nomor_surat')
                                        is-invalid
                                        @enderror">
                                    @error('nomor_surat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal SPT</label>
                                    <input type="date" name="tgl_surat" value="{{ $data->tgl_surat }}" placeholder="..Tanggal Surat.." class="form-control @error('tgl_surat')
                                        is-invalid
                                        @enderror">
                                    @error('tgl_surat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lama Pelatihan (JP) <span class="text-danger">*</span>&nbsp; <a href="javascript:void(0)" class="show-pdf pdf-jp badge badge-pill badge-info"><i class="fa-solid fa-info"></i>
                                        </a></label>
                                    <input type="number" name="lama_pendidikan" value="{{ $data->lama_pendidikan }}" placeholder="..Jam Pelajaran.." class="form-control @error('lama_pendidikan')
                                        is-invalid
                                        @enderror" required>
                                    @error('lama_pendidikan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Angkatan</label>
                                    <input type="search" name="tahun_angkatan" value="{{ $data->tahun_angkatan }}" placeholder="..Angkatan.." class="form-control @error('tahun_angkatan')
                                        is-invalid
                                        @enderror">
                                    @error('tahun_angkatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Sertifikat (STTPP) <span class="text-danger">*</span></label>
                                    <input type="search" name="nomor_sttpp" value="{{ $data->nomor_sttpp }}" placeholder="..Nomor Sertifikat.." class="form-control @error('nomor_sttpp')
                                        is-invalid @enderror" required>
                                    @error('nomor_sttpp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Sertifikat (STTPP) <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_sttpp" value="{{ $data->tgl_sttpp }}" placeholder="..Tanggal Sertifikat.." class="form-control @error('tgl_sttpp')
                                        is-invalid @enderror" required>
                                    @error('tgl_sttpp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class=" col-md-6">
                                <div class="form-group">
                                    <label style="display: block;">File Surat Laporan</label>
                                    <div class="custom-icon-pdf  file-spt-pdfview">
                                        <a id="btn-preview-file-laporan" class="style-icon-pdf" href="javascript:void(0)"><i class="fa-regular fa-file-pdf"></i></a>
                                    </div>
                                    <div class="custom-file mb-3 custom-input-pdf">
                                        <input type="file" class="custom-file-input @error('file_surat_laporan')
                                                is-invalid @enderror" name="file_surat_laporan" id="customFileSuratLaporan" value="{{ asset('Lamp_Laporan') }}/{{ $data->file_surat_laporan }}" accept="application/pdf">
                                        <label class="custom-file-label" for="customFileSuratLaporan">
                                            @if (!empty($data->file_surat_laporan))
                                            {{ $data->file_surat_laporan }}
                                            @else
                                            ..PDF Surat Laporan..
                                            @endif
                                        </label>
                                        @error('file_surat_laporan')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="display: block;">File Sertifikat (STTPP) <span class="text-danger">*</span></label>
                                    <div class="custom-icon-pdf2">
                                        <a id="btn-preview-file-sertifikat" class="style-icon-pdf2" href="javascript:void(0)"><i class="fa-regular fa-file-pdf"></i></a>
                                    </div>
                                    <div class="custom-file mb-3 custom-input-pdf2">
                                        <input type="file" class="custom-file-input @error('file_sttpp')
                                                                is-invalid @enderror" name="file_sttpp" id="customFileSertifikat" value="{{ asset('lampiran') }}/{{ $data->file_sttpp }}" accept="application/pdf">
                                        <label class="custom-file-label" for="customFileSertifikat">{{ $data->file_sttpp }}</label>
                                        @error('file_sttpp')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- @if (!auth()->user()->can('laporan-approve')) {

                            } --}}

                            @can('laporan-approve')
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="" id="status1" {{ $data->status == null ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status1">
                                            Ditinjau
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2" value="1" {{ $data->status == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status2">
                                            Disetujui
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status4" value="0" {{ $data->status == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status4">
                                            Ditolak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="alasan">
                                <div class="form-group">
                                    <label id="text-catatan"></label>
                                    <textarea name="alasan" class="form-control" style="height: 100px" placeholder="..Isikan alasan apabila laporan ditolak..">{{ strip_tags($data->alasan) }}</textarea>
                                </div>
                            </div>
                            @endcan
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

<div class="modal fade" id="viewpdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="height: 100%;">
                    <div id="sectionViewPdf">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-close-preview" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewpdfSertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="height: 100%;">
                    <div id="sectionViewPdfSertifikat">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-close-preview" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-pdf">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal-title"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <embed src="" type="application/pdf" width="100%" height="600px" id="pdf">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#sectionViewPdf').append(
            '<object data="{{ asset('
            Lamp_Laporan ') }}/{{ $data->file_surat_laporan }}" type="application/pdf" width="100%" height="600px">' +
            '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
        );

        $('#customFileSuratLaporan').on('change', function() {
            var fileName = document.getElementById("customFileSuratLaporan").files[0].name;
            if (fileName.length > 28) {
                fileName = fileName.substring(0, 28) + '...';
            };
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#btn-preview-file-laporan').click(function() {
            $('#viewpdf').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });

            var [file] = document.getElementById("customFileSuratLaporan").files;
            const urlFile = URL.createObjectURL(file);
            $('#sectionViewPdf').empty();
            $('#sectionViewPdf').append(
                '<object width="100%" style="min-height: 600px;" type="application/pdf" data="' +
                urlFile +
                '">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#sectionViewPdfSertifikat').append(
            '<object data="{{ asset('
            Lamp_Sertifikat ') }}/{{ $data->file_sttpp }}" type="application/pdf" width="100%" height="600px">' +
            '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
        );

        $('#customFileSertifikat').on('change', function() {
            var fileName = document.getElementById("customFileSertifikat").files[0].name;
            if (fileName.length > 28) {
                fileName = fileName.substring(0, 28) + '...';
            };
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#btn-preview-file-sertifikat').click(function() {
            $('#viewpdfSertifikat').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });

            var [file] = document.getElementById("customFileSertifikat").files;
            const urlFile = URL.createObjectURL(file);
            $('#sectionViewPdfSertifikat').empty();
            $('#sectionViewPdfSertifikat').append(
                '<object width="100%" height="600px" type="application/pdf" data="' + urlFile +
                '">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>

<script src="{{ asset('assets/js/request-page/laporan-update.js') }}"></script>
<script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

<script>
    $('#jenis_diklat').change(function() {
        $('#sub_jenis_diklat').empty();
        $('#rumpun_diklat').empty();
        var jenis_diklat = $(this).val();
        if (jenis_diklat) {
            $.ajax({
                type: "GET",
                url: "{{ url('/get/sub-jenis-diklat/') }}/" + jenis_diklat,
                success: function(res) {
                    if (res) {
                        $("#sub_jenis_diklat").empty();
                        $("#sub_jenis_diklat").append(
                            '<option value="">..Sub Jenis Diklat..</option>');
                        $.each(res, function(key, value) {
                            if (value.sub_jenis_diklat ==
                                "{{ $data->sub_jenis_diklat }}") {
                                $("#sub_jenis_diklat").append('<option value="' + value
                                    .sub_jenis_diklat + '" selected>' + value
                                    .sub_jenis_diklat + ' </option>');
                            } else {
                                $("#sub_jenis_diklat").append('<option value="' + value
                                    .sub_jenis_diklat + '">' + value.sub_jenis_diklat +
                                    ' </option>');
                            }
                        });

                    } else {
                        $("#sub_jenis_diklat").empty();
                    }
                }
            });
        } else {
            $("#sub_jenis_diklat").empty();
        }
    });


    $('#sub_jenis_diklat').change(function() {
        $("#rumpun_diklat").empty();
        var jenis_diklat = $('#jenis_diklat').val();
        var sub_jenis_diklat = $(this).val();
        if (jenis_diklat) {
            $.ajax({
                type: "GET",
                url: "{{ url('/get/rumpun-diklat/') }}?jenis_diklat=" + jenis_diklat +
                    '&sub_jenis_diklat=' + sub_jenis_diklat,
                success: function(res) {
                    if (res) {
                        $("#rumpun_diklat").empty();
                        $("#rumpun_diklat").append('<option value="">..Rumpun Diklat..</option>');
                        $.each(res, function(key, value) {
                            if (value.rumpun_diklat == "{{ $data->rumpun_diklat }}") {
                                $("#rumpun_diklat").append('<option value="' + value
                                    .rumpun_diklat + ',' + value.id_diklat + ',' + value
                                    .id_siasn + '" selected>' + value
                                    .rumpun_diklat + ' </option>');
                            } else {
                                $("#rumpun_diklat").append('<option value="' + value
                                    .rumpun_diklat + ',' + value.id_diklat + ',' + value
                                    .id_siasn + '">' + value.rumpun_diklat +
                                    ' </option>');
                            }
                        });
                    } else {
                        $("#rumpun_diklat").empty();
                    }
                }
            });
        } else {
            $("#sub_jenis_diklat").empty();
        }
    });

    if ($('#jenis_diklat').val()) {
        // trigger change event
        $('#jenis_diklat').trigger('change');
        setTimeout(function() {
            if ($('#sub_jenis_diklat').val()) {
                $('#sub_jenis_diklat').trigger('change');
            }
        }, 700);

    }

    $(document).ready(function() {
        if ("{{ $data->file_surat_laporan }}" == "") {
            $('.file-spt-pdfview').hide();
        } else {
            $('.file-spt-pdfview').show();
        }
    });
</script>

<script>
    $(document).ready(function() {
        if ($('#status4').is(':checked')) {
            $('#alasan').show();
            $('#text-catatan').html("Alasan Jika Ditolak <span class='text-danger'>*</span>");
        } else if ($('#status2').is(':checked')) {
            $('#alasan').show();
            $('#status1').attr('disabled', true);
            $('#text-catatan').html("Catatan <span class='text-danger'>*</span>");
        } else {
            $('#alasan').hide();
        }

    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
@endpush