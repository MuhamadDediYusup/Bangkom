@extends('layout.main-layout')

@section('content')
@include('partials.section_header')
@push('css')
<style>
    .custom-hover input:hover{
        cursor: pointer;
    }
</style>
@endpush

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk mengubah file petunjuk penggunaan.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pendukung.petunjuk.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Petunjuk Penggunaan</label>
                                    <div class="custom-file mb-3 custom-hover">
                                        <input type="file" class="custom-file-input" name="file_petunjuk" id="customFile" value="{{ asset('petunjuk_file') }}/{{ $petunjuk->file_petunjuk }}" required accept="application/pdf">
                                        <label class="custom-file-label" for="customFile">{{ $petunjuk->file_petunjuk  }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lihat Petunjuk Penggunaan</label>
                                    <div>
                                        <a id="btn-preview-file" class="btn btn-warning" href="javascript:void(0)" >Lihat File</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <a href="{{ route('pendukung.petunjuk') }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection

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

@push('js')
<script>
    $(document).ready(function () {
        $('#sectionViewPdf').append(
            '<object data="{{ asset('petunjuk_file') }}/{{ $petunjuk->file_petunjuk }}#toolbar=0&navpanes=0" type="application/pdf" width="100%" height="100%">' + 
            '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
        );
        $('#customFile').on('change',function(){
            var fileName = document.getElementById("customFile").files[0].name;
            if(fileName.length > 28){
                fileName = fileName.substring(0,28) + '...';
            };
            $(this).next('.custom-file-label').html(fileName);
        });
        $('#btn-preview-file').click(function() {
            $('#viewpdf').modal({
                backdrop: 'static',
                keyboard: false,
                show: true,
            });
            var [file] = document.getElementById("customFile").files;
            const urlFile = URL.createObjectURL(file);
            $('#sectionViewPdf').empty();
            $('#sectionViewPdf').append(
                '<object width="100%" height="100%" type="application/pdf" data="' + urlFile + '">' +
                '<p>Maaf, File PDF anda tidak bisa ditampilkan.</p></object>'
            );
        });
    });
</script>
@endpush