@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan usulan untuk pengembangan kompetensi.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('usulan_bangkom.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input name="nama" type="text" value="{{ $pegawai->nama_lengkap }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input name="nip" type="text" value="{{ $pegawai->nip }}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <input name="jabatan" type="text" value="{{ $pegawai->jabatan }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="">..Jenis Diklat..</option>
                                        @foreach ($jenis_diklat as $item)
                                        <option value="{{ $item->jenis_diklat }}">
                                            {{ $item->jenis_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sub Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="sub_jenis_diklat" id="sub_jenis_diklat"
                                        required>
                                        <option value="">..Sub Jenis Diklat..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Rumpun Diklat <span class="text-danger">*</span> &nbsp; <a
                                            href="javascript:void(0)" class="show-pdf badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <select class="form-control select2" name="rumpun_diklat" id="rumpun_diklat"
                                        required>
                                        <option value="">..Rumpun Diklat..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat <span class="text-danger">*</span></label>
                                    <input name="nama_diklat" type="search" placeholder="..Nama Diklat.."
                                        maxlength="250" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dasar Usulan <span class="text-danger">*</span> </label>
                                    <select class="form-control select2" name="dasar_usulan" id="dasar_usulan" required>
                                        <option value="">..Dasar Usulan..</option>
                                        {{-- <option value="Standar Kompetensi Jabatan (SKJ)">Standar Kompetensi Jabatan
                                            (SKJ)</option> --}}
                                        <option value="Human Capital Development Plan (HCDP)">Human Capital Development
                                            Plan (HCDP)</option>
                                        <option value="Analisis Kebutuhan Diklat (AKD)">Analisis Kebutuhan Diklat (AKD)
                                        <option value="Penawaran">Penawaran</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Surat Permohonan beserta Penawaran <span class="text-danger">*</span></label>
                                    <div class="custom-file mb-3 custom-hover">
                                        <input type="file" class="custom-file-input @error('file_sttpp')
                                        is-invalid
                                        @enderror" name="file_surat_penawaran" id="costumFileSuratPenawaran"
                                            accept="application/pdf">
                                        <label class="custom-file-label" id="constum-file-sertifikat"
                                            for="costumFileSuratPenawaran">..PDF
                                            Surat Permohonan dan Penawaran..</label>
                                        @error('file_sttpp')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Usulan</button>
                        <a href="{{ route('form_usulan') }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

{{-- modal pdf --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal-pdf">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Petunjuk Rumpun Diklat</h5>
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
<script src="{{ asset('assets/js/request-page/usulan-create.js') }}"></script>
<script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

<script>
    $("#jenis_diklat").change(function() {
            var jenis_diklat = $(this).val();
            if (jenis_diklat) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get/sub-jenis-diklat/') }}/" + jenis_diklat,
                    success: function(res) {
                        if (res) {
                            $("#sub_jenis_diklat").empty();
                            $("#sub_jenis_diklat").append(
                                '<option value="">..Sub Jenis Diklat..</option>'
                            );
                            $.each(res, function(key, value) {
                                $("#sub_jenis_diklat").append(
                                    '<option value="' +
                                    value.sub_jenis_diklat +
                                    '">' +
                                    value.sub_jenis_diklat +
                                    "</option>"
                                );
                            });
                        } else {
                            $("#sub_jenis_diklat").empty();
                        }
                    },
                });
            } else {
                $("#sub_jenis_diklat").empty();
            }
        });

        $("#sub_jenis_diklat").change(function() {
            var jenis_diklat = $("#jenis_diklat").val();
            var sub_jenis_diklat = $(this).val();

            var url = "{{ url('/get/rumpun-diklat/') }}?jenis_diklat=" + jenis_diklat +
                        '&sub_jenis_diklat=' + sub_jenis_diklat;

                $.ajax({
                type: "GET",
                url: "{{ url('/get/rumpun-diklat/') }}?jenis_diklat=" + jenis_diklat +
                    '&sub_jenis_diklat=' + sub_jenis_diklat,
                success: function(res) {

                    if (res) {
                        $("#rumpun_diklat").empty();
                        $("#rumpun_diklat").append(
                            '<option value="">..Rumpun Diklat..</option>'
                        );
                        $.each(res, function(key, value) {

                            console.log(value.rumpun_diklat);

                            $("#rumpun_diklat").append('<option value="' + value.rumpun_diklat + "," + value.id_diklat + "," + value.id_siasn + "," + value.sertifikat_siasn + '">' + value.rumpun_diklat + "</option>");
                        });
                    } else {
                        $("#rumpun_diklat").empty();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + error);
                }
            });

        });
        $(document).ready(function() {
    $('.select2').select2();
});

</script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
@endpush