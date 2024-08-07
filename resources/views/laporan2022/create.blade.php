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
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                    <form action="{{ route('laporan_2022.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="search" name="nama" value="{{ $pegawai->nama_lengkap }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="search" name="nip" value="{{ $pegawai->nip }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="search" name="jabatan" value="{{ $pegawai->jabatan }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis Diklat</label>
                                    <select class="form-control select2 @error('jenis_diklat')
                                    is-invalid
                                    @enderror" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="">..Jenis Diklat..</option>
                                        @foreach ($jenis_diklat as $item)
                                        <option value="{{ $item->jenis_diklat }}">
                                            {{ $item->jenis_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_diklat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sub Jenis Diklat</label>
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
                                    <label>Rumpun Diklat &nbsp; <a href="javascript:void(0)"
                                            class="show-pdf pdf-rumpun-diklat badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <select class="form-control select2 @error('rumpun_diklat')
                                    is-invalid
                                    @enderror" name="rumpun_diklat" id="rumpun_diklat" required>
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
                                    <label>Nama Diklat</label>
                                    <input name="nama_diklat" type="text" placeholder=".. Nama Diklat .."
                                        value="{{ old('nama_diklat') }}" class="form-control @error('nama_diklat')
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
                                    <label>Tempat</label>
                                    <input type="search" name="tempat_diklat" value="{{ old('tempat_diklat') }}"
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
                                    <label>Penyelenggara</label>
                                    <input type="search" name="penyelenggara_diklat"
                                        placeholder="..Penyelenggara Bang Kom.."
                                        value="{{ old('penyelenggara_diklat') }}" class="form-control @error('penyelenggara_diklat')
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
                                    <label>Mulai</label>
                                    <input type="date" id="mulai" name="tahun_mulai" value="{{ old('tahun_mulai') }}"
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
                                    <label>Selesai</label>
                                    <input type="date" name="tahun_selesai" value="{{ old('tahun_selesai') }}"
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
                                    <label>Lama Pelatihan (JP) &nbsp; <a href="javascript:void(0)"
                                            class="show-pdf pdf-jp badge badge-pill badge-info"><i
                                                class="fa-solid fa-info"></i>
                                        </a></label>
                                    <input type="number" name="lama_pendidikan" value="{{ old('lama_pendidikan') }}"
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
                                    <input type="search" name="tahun_angkatan" value="{{ old('tahun_angkatan') }}"
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
                                    <label>Tanggal Sertifikat (STTPP)</label>
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
                                        is-invalid @enderror" name="file_surat_laporan" id="customFile"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFile">..PDF Surat
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
                                    <label>File Sertifikat (STTPP)</label>
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
                        <a href="{{ route('laporan_2022.index') }}" class="btn btn-danger">Batal</a>
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

<script>
    $('#customFile').on('change',function(){
        var fileName = document.getElementById("customFile").files[0].name;
        $(this).next('.custom-file-label').html(fileName);
    })
</script>
<script>
    $('#customFileSertifikat').on('change',function(){
        var fileName = document.getElementById("customFileSertifikat").files[0].name;
        $(this).next('#constum-file-sertifikat').html(fileName);
    })
</script>

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>


{{-- show pdf --}}
<script>
    $(document).ready(function () {
        $('.pdf-rumpun-diklat').click(function () {
            var pdf = $(this).data('pdf');
            $('#pdf').attr('src', 'https://bangkom.slemankab.go.id/petunjuk_file/Rumpun%20Diklat.pdf');
            $('#modal-title').html('Petunjuk Rumpun Diklat');
            $('#modal-pdf').modal('show');
        });
    });
    $(document).ready(function () {
        $('.pdf-jp').click(function () {
            var pdf = $(this).data('pdf');
            $('#pdf').attr('src', 'https://bangkom.slemankab.go.id/petunjuk_file/Rumpun%20Diklat.pdf');
            $('#modal-title').html('Petunjuk Pengisian JP');
            $('#modal-pdf').modal('show');
        });
    });
</script>

<script>
    $('#jenis_diklat').change(function(){
        var jenis_diklat = $(this).val();
        if(jenis_diklat){
            $.ajax({
                type:"GET",
                url:"{{url('/get/sub-jenis-diklat/')}}/"+jenis_diklat,
                success:function(res){
                    if(res){
                        $("#sub_jenis_diklat").empty();
                        $("#sub_jenis_diklat").append('<option value="">..Sub Jenis Diklat..</option>');
                        $.each(res,function(key,value){
                            $("#sub_jenis_diklat").append('<option value="'+value.sub_jenis_diklat+'">'+value.sub_jenis_diklat+'</option>');
                        });

                    }else{
                        $("#sub_jenis_diklat").empty();
                    }
                }
            });
        }else{
            $("#sub_jenis_diklat").empty();
        }
    });
</script>
<script>
    $('#sub_jenis_diklat').change(function(){
        console.log('test');
        var jenis_diklat = $('#jenis_diklat').val();
        var sub_jenis_diklat = $(this).val();
        if(jenis_diklat){
            $.ajax({
                type:"GET",
                url:"{{url('/get/rumpun-diklat/')}}?jenis_diklat="+jenis_diklat+'&sub_jenis_diklat='+sub_jenis_diklat,
                success:function(res){
                    if(res){
                        $("#rumpun_diklat").empty();
                        $("#rumpun_diklat").append('<option value="">..Rumpun Diklat..</option>');
                        $.each(res,function(key,value){
                            $("#rumpun_diklat").append('<option value="'+value.rumpun_diklat+','+value.id_diklat+','+value.id_siasn+'">'+value.rumpun_diklat+'</option>');
                        });
                    }else{
                        $("#rumpun_diklat").empty();
                    }
                }
            });
        }else{
            $("#sub_jenis_diklat").empty();
        }
    });
</script>

@endpush

@push('css')
<style>
    .show-pdf {
        font-size: 8px;
        padding: .2rem .4rem;
    }

    /* make effect beep for class show-pdf */
    .show-pdf {
        animation: beep 1s infinite;
    }

    @keyframes beep {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
@endpush