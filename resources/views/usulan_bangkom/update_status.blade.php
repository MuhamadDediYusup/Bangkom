@extends('layout.main-layout')

@section('content')
@include('partials.section_header')
@if (!empty($usulan->file_surat_penawaran))
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

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan edit status pengembangan kompetensi.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('update_status.edit', $usulan->id_usul) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input name="nama" type="text" value="{{ $usulan->nama_lengkap }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input name="nip" type="text" value="{{ $usulan->nip }}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <input name="jabatan" type="text" value="{{ $usulan->jabatan }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Diklat <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="">..Jenis Diklat..</option>
                                        @foreach ($jenis_diklat as $item)
                                        <option value="{{ $item->jenis_diklat }}" {{ $item->jenis_diklat ==
                                            $usulan->jenis_diklat ? 'selected' : '' }}>
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
                                    <label>Rumpun Diklat <span class="text-danger">*</span></label> &nbsp; <a
                                        href="javascript:void(0)"
                                        class="show-pdf pdf-rumpun-diklat badge badge-pill badge-info"><i
                                            class="fa-solid fa-info"></i>
                                    </a>
                                    <select class="form-control select2" name="rumpun_diklat" id="rumpun_diklat"
                                        required>
                                        <option value="">..Rumpun Diklat..</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat <span class="text-danger">*</span></label>
                                    <input name="nama_diklat" type="text" id="nama_diklat" maxlength="250"
                                        value="{{ strip_tags($usulan->nama_diklat) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dasar Usulan <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="dasar_usulan" id="dasar_usulan" required>
                                        <option value="">..Dasar Usulan..</option>
                                        {{-- <option {{ $usulan->dasar_usulan == 'Standar Kompetensi Jabatan (SKJ)' ?
                                            'selected' : '' }}
                                            value="Standar Kompetensi Jabatan (SKJ)">Standar
                                            Kompetensi Jabatan
                                            (SKJ)</option> --}}
                                        <option {{ $usulan->dasar_usulan == 'Analisis Kebutuhan Diklat (AKD)' ?
                                            'selected' : '' }}
                                            value="Analisis Kebutuhan Diklat (AKD)">Analisis
                                            Kebutuhan
                                            Diklat (AKD)
                                        </option>
                                        <option {{ $usulan->dasar_usulan == 'Human Capital Development Plan (HCDP)' ?
                                            'selected' : '' }}
                                            value="Human Capital Development Plan (HCDP)">Human Capital Development Plan
                                            (HCDP)</option>
                                        <option {{ $usulan->dasar_usulan == 'Penawaran' ? 'selected' : '' }}
                                            value="Penawaran">Penawaran</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="display: block;">Surat Penawaran</label>
                                    <div class="custom-icon-pdf">
                                        <a id="btn-preview-file" class="style-icon-pdf show-pdf-surat-penawaran"
                                            href="javascript:void(0)"><i class="fa-regular fa-file-pdf"></i></a>
                                    </div>
                                    <div class="custom-file mb-3 custom-input-pdf">
                                        <input type="file" class="custom-file-input @error('file_sttpp')
                                        is-invalid
                                        @enderror" name="file_surat_penawaran" id="costumFileSuratPenawaran"
                                            value="{{ asset('Lamp_Surat_Penawaran') }}/{{ $usulan->file_surat_penawaran }}"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="costumFileSuratPenawaran">
                                            @if (!empty($usulan->file_surat_penawaran))
                                            {{ $usulan->file_surat_penawaran }}
                                            @else
                                            ..PDF Surat Laporan..
                                            @endif
                                        </label>
                                        @error('file_sttpp')
                                        <div class="invalid-feedback mt-3">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @can('usulan-approve')
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1" value=""
                                            {{ $usulan->status == '' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status1">
                                            Ditinjau
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2"
                                            value="1" {{ $usulan->status == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status2">
                                            Disetujui
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status4"
                                            value="0" {{ $usulan->status == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status4">
                                            Ditolak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            <div class="col-md-6" id="alasan">
                                <div class="form-group">
                                    <label>Alasan Jika Ditolak <span class="text-danger">*</span></label>
                                    <textarea name="alasan" id="input_alasan" class="form-control" style="height: 100px"
                                        placeholder="..Isikan alasan apabila usulan ditolak..">{{ $usulan->alasan }}</textarea>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Usulan</button>
                        <a href="{{ route('usulan_bangkom') }}" class="btn btn-danger">Batal</a>
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
                <h5 class="modal-title">File Surat Penawaran</h5>
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
    $("#costumFileSuratPenawaran").on("change", function() {
            var fileName = document.getElementById("costumFileSuratPenawaran").files[0]
                .name;
            $(this).next(".custom-file-label").html(fileName);
        });
</script>
<script src="{{ asset('assets/js/request-page/usulan-update.js') }}"></script>
<script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

<script>
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
                                    "{{ $usulan->sub_jenis_diklat }}") {
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
                                if (value.rumpun_diklat == "{{ $usulan->rumpun_diklat }}") {
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

        $(document).ready(function() {
            $('.show-pdf-surat-penawaran').click(function() {
                var pdf = "{{ asset('Lamp_Surat_Penawaran') }}/{{ $usulan->file_surat_penawaran }}";
                $('#pdf').attr('src', pdf);
                $('#modal-pdf').modal('show');
            });
        });

        if ($("#jenis_diklat").val()) {
            $("#jenis_diklat").trigger("change");
            setTimeout(function() {
                if ($("#sub_jenis_diklat").val()) {
                    $("#sub_jenis_diklat").trigger("change");
                }
            }, 800);
        }

        $(document).ready(function() {
            if ("{{ $usulan->file_surat_penawaran }}" == "") {
                $('.file-spt-pdfview').hide();
                $('.custom-icon-pdf').hide();
            } else {
                $('.file-spt-pdfview').show();
                $('.custom-icon-pdf').show();
            }
        });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
@endpush