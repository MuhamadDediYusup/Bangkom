@extends('layout.main-layout')

@section('content')
@include('partials.section_header')
@push('css')
<style>
    .custom-hover input:hover {
        cursor: pointer;
    }
</style>
@endpush

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan pengiriman pengembangan kompetensi.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                    <form action="{{ route('pengiriman.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="id_usul" value="{{ $usulan->id_usul }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="{{ $usulan->nama_lengkap }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip" value="{{ $usulan->nip }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bang Kom <span class="text-danger">*</span></label>
                                    <input name="nama_bangkom" type="text"
                                        value="{{ $usulan->jenis_diklat }} - {{ $usulan->sub_jenis_diklat}} - {{ $usulan->rumpun_diklat  }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Diklat <span class="text-danger">*</span></label>
                                    <input name="nama_diklat" type="text" value="{{ $usulan->nama_diklat }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat <span class="text-danger">*</span></label>
                                    <input type="search" name="tempat_diklat" placeholder="..Tempat Bang Kom.."
                                        class="form-control @error('tempat_diklat') is-invalid @else '' @enderror"
                                        value="{{ old('tempat_diklat') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penyelenggara <span class="text-danger">*</span></label>
                                    <input type="search" name="penyelenggara_diklat"
                                        placeholder="..Penyelenggara Bang Kom.."
                                        class="form-control @error('penyelenggara_diklat') is-invalid @else '' @enderror"
                                        value="{{ old('penyelenggara_diklat') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_mulai" placeholder="..Tangga Mulai.."
                                        class="form-control @error('tgl_mulai') is-invalid @else '' @enderror"
                                        value="{{ old('tgl_mulai') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_selesai" placeholder="..Tanggal Selesai.."
                                        class="form-control @error('tgl_selesai') is-invalid @else '' @enderror"
                                        value="{{ old('tgl_selesai') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_surat" placeholder="..Nomor Surat.."
                                        class="form-control @error('nomor_surat') is-invalid @else '' @enderror"
                                        value="{{ old('nomor_surat') }}" required>
                                    @if($errors->has('nomor_surat'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('nomor_surat') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Surat <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_surat" placeholder="..Tanggal Surat.."
                                        class="form-control @error('tgl_surat') is-invalid @else '' @enderror"
                                        value="{{ old('tgl_surat') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Surat (SPT)</label>
                                    <div class="custom-file mb-3 custom-hover">
                                        <input type="file"
                                            class="custom-file-input @error('file_spt') is-invalid @else '' @enderror"
                                            value="{{ old('file_spt') }}" name="file_spt" id="customFileSPT"
                                            accept="application/pdf">
                                        <label class="custom-file-label" for="customFileSPT">...Pilih File...</label>
                                        @if($errors->has('file_spt'))
                                        <div class="invalid-feedback mt-3">
                                            {{ $errors->first('file_spt') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <a href="{{ route('usulan_bangkom') }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/js/request-page/check-file-upload.js') }}"></script>

<script>
    $('#customFileSPT').on('change',function(){
        var fileName = document.getElementById("customFileSPT").files[0].name;
        $(this).next('.custom-file-label').html(fileName);
    })
</script>

@endpush