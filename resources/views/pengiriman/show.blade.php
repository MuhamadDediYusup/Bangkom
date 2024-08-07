@extends('layout.main-layout')

@section('content')
    @include('partials.section_header')

    @if (!empty($pengiriman[0]->file_spt))
        @push('css')
            <style class=".file-spt-pdfview">
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
        <p class="section-lead">Form ini digunakan untuk melakukan edit pengiriman pengembangan kompetensi.</p>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                {!! implode('', $errors->all('<div>:message</div>')) !!}
                            </div>
                        @endif
                        <form action="{{ route('pengiriman.update', $pengiriman[0]->id_pengiriman) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->nama_lengkap }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIP <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->nip }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Bang Kom <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->jenis_diklat }} -
                                            {{ $pengiriman[0]->sub_jenis_diklat }} -
                                            {{ $pengiriman[0]->rumpun_diklat }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama Diklat <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->nama_diklat }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tempat <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->tempat_diklat }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Penyelenggara <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ $pengiriman[0]->penyelenggara_diklat }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ strip_tags($pengiriman[0]->tgl_mulai) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ strip_tags($pengiriman[0]->tgl_selesai) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Surat (SPT) <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ strip_tags($pengiriman[0]->nomor_surat) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Surat (SPT) <span class="text-danger">*</span></label>
                                        <span class="form-control">{{ strip_tags($pengiriman[0]->tgl_surat) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="display: block;">File Surat (SPT)</label>
                                        <div class="custom-icon-pdf file-spt-pdfview">
                                            <a id="btn-preview-file" class="style-icon-pdf" href="javascript:void(0)"><i
                                                    class="fa-regular fa-file-pdf"></i></a>
                                        </div>
                                        <div class="custom-file mb-3 custom-input-pdf">
                                            <input type="file"
                                                class="custom-file-input @error('file_spt')
                                        is-invalid
                                        @enderror"
                                                name="file_spt" id="customFileSPT"
                                                value="{{ asset('surat_pengiriman') }}/{{ $pengiriman[0]->file_spt }}"
                                                accept="application/pdf">
                                            <label class="custom-file-label" for="customFileSPT">
                                                @if (!empty($pengiriman[0]->file_spt))
                                                    {{ $pengiriman[0]->file_spt }}
                                                @else
                                                    ..PDF Surat Laporan..
                                                @endif
                                            </label>
                                            @error('file_spt')
                                                <div class="invalid-feedback mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="javascript:history.back()" class="btn btn-primary">Kembali</a>
                            </div>
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="min-height: 450px;">
                        <div id="sectionViewPdf">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-close-preview"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#sectionViewPdf').append(
                '<object data="{{ asset('Lamp_SPT') }}/{{ $pengiriman[0]->file_spt }}" type="application/pdf" width="100%" height="480px">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );

            $('#customFileLaporan').on('change', function() {
                var fileName = document.getElementById("customFileLaporan").files[0].name;
                if (fileName.length > 28) {
                    fileName = fileName.substring(0, 28) + '...';
                };
                $(this).next('.custom-file-label').html(fileName);
            });

            $('#btn-preview-file').click(function() {
                $('#viewpdf').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });

                var [file] = document.getElementById("customFileLaporan").files;
                const urlFile = URL.createObjectURL(file);
                $('#sectionViewPdf').empty();
                $('#sectionViewPdf').append(
                    '<object width="100%" height="100%" type="application/pdf" data="' + urlFile +
                    '">' +
                    '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
                );
            });
        });

        $('#customFileSPT').on('change', function() {
            var fileName = document.getElementById("customFileSPT").files[0].name;
            $(this).next('.custom-file-label').html(fileName);
        });

        $(document).ready(function() {
            if ("{{ $pengiriman[0]->file_spt }}" == "") {
                $('.file-spt-pdfview').hide();
            } else {
                $('.file-spt-pdfview').show();
            }
        });
    </script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
@endpush
