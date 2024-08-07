@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan pelaporan pengembangan kompetensi.</p>

    <div class="row">

        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                    <form action="{{ route('laporan.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="search" name="nama" value="{{ $data->nama_lengkap }}" readonly
                                        class="form-control">
                                    <input type="hidden" name="id_usul"
                                        value="@if (!empty($usul)) {{ $data->id_usul }} @endif">
                                    <input type="hidden" name="id_pengiriman"
                                        value="@if (!empty($usul)) {{ $data->id_pengiriman }} @endif">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="search" name="nip" value="{{ $data->nip }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="search" name="jabatan" value="{{ $data->jabatan }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="">..Jenis Diklat..</option>
                                        @foreach ($jenis_diklat as $item)
                                        <option value="{{ $item->jenis_diklat }}" {{ $item->jenis_diklat ==
                                            $data->jenis_diklat ? 'selected' : '' }}>
                                            {{ $item->jenis_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sub Jenis Diklat <span class="text-danger">*</span>&nbsp; <a
                                            href="javascript:void(0)"
                                            class="show-pdf pdf-bentuk-jalur badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <select class="form-control select2 @error('sub_jenis_diklat')
                                    is-invalid
                                    @enderror" name="sub_jenis_diklat" id="sub_jenis_diklat" required>
                                        <option value="">..Sub Jenis Diklat..</option>
                                    </select>

                                    @error('sub_jenis_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Rumpun Diklat <span class="text-danger">*</span> &nbsp; <a
                                            href="javascript:void(0)"
                                            class="show-pdf pdf-rumpun-diklat badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <select class="form-control select2  @error('rumpun_diklat') is-invalid @enderror"
                                        name="rumpun_diklat" id="rumpun_diklat" required>
                                        <option value="">..Rumpun Diklat..</option>
                                    </select>
                                    @error('rumpun_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat <span class="text-danger">*</span> </label>
                                    <input name="nama_diklat" type="text" placeholder=".. Nama Diklat .."
                                        maxlength="250"
                                        value="{{ old('nama_diklat') }}@if (!empty($usul)) {{ $data->nama_diklat }} @endif"
                                        class="form-control @error('nama_diklat')
                                        is-invalid
                                        @enderror" required>
                                    @error('nama_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat <span class="text-danger">*</span> </label>
                                    <input type="search" name="tempat_diklat"
                                        value="{{ old('tempat_diklat') }}@if (!empty($usul)) {{ $data->tempat_diklat }} @endif"
                                        placeholder="..Tempat Bang Kom.." class="form-control @error('tempat_diklat')
                                        is-invalid
                                        @enderror" required>
                                    @error('tempat_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penyelenggara <span class="text-danger">*</span> </label>
                                    <input type="search" name="penyelenggara_diklat"
                                        placeholder="..Penyelenggara Bang Kom.."
                                        value="{{ old('penyelenggara_diklat') }}@if (!empty($usul)) {{ $data->penyelenggara_diklat }} @endif"
                                        class="form-control @error('penyelenggara_diklat')
                                        is-invalid
                                        @enderror" required>
                                    @error('penyelenggara_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mulai <span class="text-danger">*</span></label>
                                    <input type="date" id="mulai" name="tahun_mulai"
                                        value="{{ old('tahun_mulai') }}@if (!empty($usul)) {{ $data->tgl_mulai }} @endif"
                                        placeholder="..Tangga Mulai.." class="form-control @error('tahun_mulai')
                                        is-invalid
                                        @enderror" required>
                                    @error('tahun_mulai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selesai <span class="text-danger">*</span></label>
                                    <input type="date" name="tahun_selesai"
                                        value="{{ old('tahun_selesai') }}@if (!empty($usul)) {{ $data->tgl_selesai }} @endif"
                                        placeholder="..Tanggal Selesai.." class="form-control @error('tahun_selesai')
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
                                    <input type="text" name="nomor_surat"
                                        value="{{ old('nomor_surat') }}@if (!empty($usul)) {{ $data->nomor_surat }} @endif"
                                        placeholder="..Nomor SPT.." class="form-control @error('nomor_surat')
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
                                    <input type="date" name="tgl_surat"
                                        value="{{ old('tgl_surat') }}@if (!empty($usul)) {{ $data->tgl_surat }} @endif"
                                        placeholder="..Tanggal Surat.." class="form-control @error('tgl_surat')
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
                                    <label>Lama Pelatihan (JP) <span class="text-danger">*</span> &nbsp; <a
                                            href="javascript:void(0)"
                                            class="show-pdf pdf-jp badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <input type="number" name="lama_pendidikan"
                                        value="{{ old('lama_pendidikan') }}@if (!empty($usul)) {{ $data->lama_pendidikan }} @endif"
                                        placeholder="..Jam Pelajaran.." class="form-control @error('lama_pendidikan')
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
                                    <label>Angkatan </label>
                                    <input type="search" name="tahun_angkatan"
                                        value="{{ old('tahun_angkatan') }}@if (!empty($usul)) {{ $data->tahun_angkatan }} @endif"
                                        placeholder="..Angkatan.." class="form-control @error('tahun_angkatan')
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
                                    <input type="search" name="nomor_sttpp" value="{{ old('nomor_sttpp') }}"
                                        placeholder="..Nomor Sertifikat.." class="form-control" required>
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
                                    <input type="date" name="tgl_sttpp" value="{{ old('tgl_sttpp') }}"
                                        placeholder="..Tanggal Sertifikat.." class="form-control @error('tgl_sttpp')
                                        is-invalid @enderror" required>
                                    @error('tgl_sttpp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Surat Laporan</label>
                                    <div class="custom-file mb-3 custom-hover">
                                        <input type="file" class="custom-file-input @error('file_surat_laporan')
                                        is-invalid @enderror" name="file_surat_laporan" id="customFileSuratLaporan"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFileSuratLaporan">..PDF Surat
                                            Laporan..</label>
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
                                    <label>File Sertifikat (STTPP) <span class="text-danger">*</span></label>
                                    <div class="custom-file mb-3 custom-hover">
                                        <input type="file" class="custom-file-input @error('file_sttpp')
                                        is-invalid
                                        @enderror" name="file_sttpp" id="customFileSertifikat" required
                                            accept="application/pdf">
                                        <label class="custom-file-label" id="constum-file-sertifikat"
                                            for="customFileSertifikat">..PDF
                                            Sertifikat..</label>
                                        @error('file_sttpp')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan Laporan</button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
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

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script src="{{ asset('assets/js/request-page/laporan-create.js') }}"></script>
<script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

<script>
    $(document).ready(function() {
            $(".pdf-rumpun-diklat").click(function() {
                var pdf = $(this).data("pdf");
                $("#pdf").attr(
                    "src",
                    "https://bangkom.slemankab.go.id/petunjuk_file/Rumpun%20Diklat.pdf"
                );
                $("#modal-title").html("Petunjuk Rumpun Diklat");
                $("#modal-pdf").modal("show");
            });
        });
        $(document).ready(function() {
            $(".pdf-jp").click(function() {
                var pdf = $(this).data("pdf");
                $("#pdf").attr(
                    "src",
                    "https://bangkom.slemankab.go.id/petunjuk_file/Konversi%20JP.pdf"
                );
                $("#modal-title").html("Petunjuk Pengisian JP");
                $("#modal-pdf").modal("show");
            });
        });

        $(document).ready(function () {
            $(".pdf-bentuk-jalur").click(function () {
                var pdf = $(this).data("pdf");
                $("#pdf").attr(
                    "src",
                    "https://bangkom.slemankab.go.id/petunjuk_file/Bentuk%20dan%20Jalur.pdf"
                );
                $("#modal-title").html("Petunjuk Pengisian Sub Jenis Diklat");
                $("#modal-pdf").modal("show");
            });
        });

        $('#jenis_diklat').change(function() {
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
</script>
<script>
    $('#sub_jenis_diklat').change(function() {
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
                                        .id_siasn + ',' + value.sertifikat_siasn + '" selected>' + value
                                        .rumpun_diklat + ' </option>');
                                } else {
                                    $("#rumpun_diklat").append('<option value="' + value
                                        .rumpun_diklat + ',' + value.id_diklat + ',' + value
                                        .id_siasn + ',' + value.sertifikat_siasn + '">' + value.rumpun_diklat +
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
</script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
@endpush