@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

@if (!empty($data->file_laporan))
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
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                    <form action="{{ route('laporan.update',$data->id_lapor) }}" method="post"
                        enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <input type="hidden" value="{{ $data->id_diklat }}" name="id_diklat">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="search" disabled value="{{ $data->nama_lengkap }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" disabled value="{{ $data->nip }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Bang Kom</label>
                                    <input type="hidden" class="form-control" name="nama_bangkom"
                                        value="{{ $data->jenis_diklat }},{{ $data->sub_jenis_diklat}},{{ $data->rumpun_diklat }},{{ $data->id_diklat }},{{ $data->id_siasn }}">
                                    <select class="form-control select2" name="" disabled>
                                        <option value="">..Pengembangan Kompetensi..</option>
                                        @foreach ($master_diklat->Data as $item)
                                        <option
                                            value="{{ $item->jenis_diklat }},{{ $item->sub_jenis_diklat}},{{ $item->rumpun_diklat }},{{ $item->id_diklat }},{{ $item->id_siasn }}"
                                            {{ $item->jenis_diklat == $data->jenis_diklat && $item->sub_jenis_diklat
                                            ==
                                            $data->sub_jenis_diklat
                                            && $item->rumpun_diklat == $data->rumpun_diklat ? 'selected' : '' }}>
                                            {{ $item->jenis_diklat }} -
                                            {{ $item->sub_jenis_diklat }} -
                                            {{ $item->rumpun_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" readonly name="jenis_sub_nama_diklat"
                                        value="{{ $data->jenis_diklat }} - {{ $data->sub_jenis_diklat }} - {{ $data->rumpun_diklat }}"
                                        class="form-control"> --}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat</label>
                                    <input type="search" name="nama_diklat" value="{{ strip_tags($data->nama_diklat) }}"
                                        placeholder="..Nama Diklat.." class="form-control @error('nama_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat</label>
                                    <input type="search" name="tempat_diklat" value="{{ $data->tempat_diklat }}"
                                        placeholder="..Tempat Bang Kom.." class="form-control @error('tempat_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penyelenggara</label>
                                    <input type="search" name="penyelenggara_diklat"
                                        value="{{ $data->penyelenggara_diklat }}"
                                        placeholder="..Penyelenggara Bang Kom.." class="form-control @error('penyelenggara_diklat')
                                        is-invalid
                                        @enderror" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mulai</label>
                                    <input type="date" name="tgl_mulai" value="{{ $data->tgl_mulai }}"
                                        placeholder="..Tangga Mulai.." class="form-control @error('tgl_mulai')
                                        is-invalid
                                        @enderror">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selesai</label>
                                    <input type="date" name="tgl_selesai" value="{{ $data->tgl_selesai }}"
                                        placeholder="..Tanggal Selesai.." class="form-control @error('tahun_selesai')
                                        is-invalid
                                        @enderror">
                                    @error('tahun_selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Surat (SPT)</label>
                                    <input type="search" name="nomor_surat" value="{{ $data->nomor_surat }}"
                                        placeholder="..Nomor Surat.." class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Surat (SPT)</label>
                                    <input type="date" name="tgl_surat" value="{{ $data->tgl_surat }}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lama Pelatihan (JP)</label>
                                    <input type="number" name="lama_pendidikan" value="{{ $data->lama_pendidikan }}"
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
                                    <label>Angkatan</label>
                                    <input type="search" name="tahun_angkatan" value="{{ $data->tahun_angkatan }}"
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
                                    <label>Nomor Sertifikat (STTPP)</label>
                                    <input type="search" name="nomor_sttpp" value="{{ $data->nomor_sttpp }}"
                                        placeholder="..Nomor Sertifikat.." class="form-control @error('nomor_sttpp')
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
                                    <label>Tanggal Sertifikat (STTPP)</label>
                                    <input type="date" name="tgl_sttpp" value="{{ $data->tgl_sttpp }}"
                                        placeholder="..Tanggal Sertifikat.." class="form-control @error('tgl_sttpp')
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
                                    <div class="custom-icon-pdf file-spt-pdfview">
                                        <a id="btn-preview-file-laporan" class="style-icon-pdf"
                                            href="javascript:void(0)"><i class="fa-regular fa-file-pdf"></i></a>
                                    </div>
                                    <div class="custom-file mb-3 custom-input-pdf">
                                        <input type="file" class="custom-file-input @error('file_surat_laporan')
                                        is-invalid @enderror" name="file_surat_laporan" id="customFileLaporan"
                                            value="{{ asset('lampiran') }}/{{ $data->file_laporan }}"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFilelaporan">{{
                                            $data->file_laporan
                                            }}</label>
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
                                    <label style="display: block;">File Sertifikat (STTPP)</label>
                                    <div class="custom-icon-pdf2">
                                        <a id="btn-preview-file-sertifikat" class="style-icon-pdf2"
                                            href="javascript:void(0)"><i class="fa-regular fa-file-pdf"></i></a>
                                    </div>
                                    <div class="custom-file mb-3 custom-input-pdf2">
                                        <input type="file" class="custom-file-input @error('file_sttpp')
                                        is-invalid
                                        @enderror" name="file_sttpp" id="customFileSertifikat"
                                            value="{{ asset('lampiran') }}/{{ $data->file_sttpp }}"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFileSertifikat">{{
                                            $data->file_sttpp
                                            }}</label>
                                        @error('file_sttpp')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @can('laporan-approve')
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="" id="status1"
                                            {{ $data->status_laporan == '' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status1">
                                            Ditinjau
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2"
                                            value="1" {{ $data->status_laporan == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status2">
                                            Disetujui
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status4"
                                            value="0" {{ $data->status_laporan == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status4">
                                            Ditolak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="alasan">
                                <div class="form-group">
                                    <label>Alasan Jika Ditolak</label>
                                    <textarea name="alasan" class="form-control" style="height: 100px"
                                        placeholder="..Isikan alasan apabila laporan ditolak..">{{ strip_tags($data->alasan_laporan) }}</textarea>
                                </div>
                            </div>
                            @endcan
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                        <a href="{{ route('laporan.data') }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

<div class="modal fade" id="viewpdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="min-height: 450px;">
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

<div class="modal fade" id="viewpdfSertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="min-height: 450px;">
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
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#sectionViewPdf').append(
            '<object data="{{ asset('Lamp_Laporan') }}/{{ $data->file_laporan }}" type="application/pdf" width="100%" height="450px">' +
            '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
        );

        $('#customFileLaporan').on('change',function(){
            var fileName = document.getElementById("customFileLaporan").files[0].name;
            if(fileName.length > 28){
                fileName = fileName.substring(0,28) + '...';
            };
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#btn-preview-file-laporan').click(function() {
            $('#viewpdf').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });

            var [file] = document.getElementById("customFileLaporan").files;
            const urlFile = URL.createObjectURL(file);
            $('#sectionViewPdf').empty();
            $('#sectionViewPdf').append(
                '<object width="100%" height="450px" type="application/pdf" data="' + urlFile + '">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#sectionViewPdfSertifikat').append(
            '<object data="{{ asset('Lamp_Sertifikat') }}/{{ $data->file_sttpp }}" type="application/pdf" width="100%" height="450px">' +
            '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
        );

        $('#customFileSertifikat').on('change',function(){
            var fileName = document.getElementById("customFileSertifikat").files[0].name;
            if(fileName.length > 28){
                fileName = fileName.substring(0,28) + '...';
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
                '<object width="100%" height="450px" type="application/pdf" data="' + urlFile + '">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );
        });
    });
</script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>

<script>
    $('#status4, #status2, #status1').click(function() {
        if($('#status4').is(':checked')) {
            $('#alasan').show();
        } else {
            $('#alasan').hide();
        }
    });

    $(document).ready(function () {
        if ($('#status4').is(':checked')) {
            $('#alasan').show();
        } else {
            $('#alasan').hide();
        }
    });
</script>

<script>
    $(document).ready(function () {
        if ("{{ $data->file_laporan }}" == "") {
            $('.file-spt-pdfview').hide();
        } else {
            $('.file-spt-pdfview').show();
        }
    });
</script>
@endpush