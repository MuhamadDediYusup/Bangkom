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
                    <form action="{{ route('laporan.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="id_usul" value="{{ $data->id_usul }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="search" name="nama" value="{{ $data->nama }}" readonly
                                        class="form-control">
                                    <input type="hidden" name="id_pengiriman" value="{{ $data->id_pengiriman }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="search" name="nip" value="{{ $data->nip }}" readonly
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bang Kom</label>
                                    <input name="nama_bangkom" type="text"
                                        value="{{ $data->jenis_diklat }} - {{ $data->sub_jenis_diklat}} - {{ $data->rumpun_diklat  }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Diklat</label>
                                    <input name="nama_diklat" type="text" value="{{ $data->nama_diklat }}" class="form-control @error('nama_diklat')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="search" name="tempat_diklat" value="{{ $data->tempat_diklat }}"
                                        placeholder="..Tempat Bang Kom.." class="form-control @error('tempat_diklat')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="search" value="{{ $data->penyelenggara_diklat }}"
                                        name="penyelenggara_diklat" placeholder="..Penyelenggara Bang Kom.." class="form-control @error('penyelenggara_diklat')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="date" id="mulai" name="tahun_mulai" value="{{ $data->tgl_mulai }}"
                                        placeholder="..Tangga Mulai.." class="form-control @error('tahun_mulai')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="date" name="tahun_selesai" value="{{ $data->tgl_selesai }}"
                                        placeholder="..Tanggal Selesai.." class="form-control @error('tahun_selesai')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="text" name="nomor_surat" value="{{ $data->nomor_surat }}"
                                        placeholder="..Nomor SPT.." class="form-control @error('nomor_surat')
                                        is-invalid
                                        @enderror" readonly>
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
                                    <input type="date" name="tgl_surat" value="{{ $data->tgl_surat }}"
                                        placeholder="..Tanggal Surat.." class="form-control @error('tgl_surat')
                                        is-invalid
                                        @enderror" readonly>
                                    @error('tgl_surat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lama Pelatihan (JP)</label>
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
                        <a href="{{ route('pengiriman') }}" class="btn btn-danger">Batal</a>
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

@endpush