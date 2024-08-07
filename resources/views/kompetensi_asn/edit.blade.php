@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Form ini digunakan untuk melakukan edit pada data pengembangan kompetensi.</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kompetensiasn.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="jenis_diklat_pendidikan" value="{{ $jenis_diklat }}">
                        <input type="hidden" name="id_pegawai_pendidikan" value="{{ $diklat->id_pegawai_pendidikan }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input name="nama" type="text" value="{{ $diklat->nama_lengkap }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input name="nip" type="text" class="form-control" value="{{ $diklat->nip }}"
                                        readonly>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jenis Diklat</label>
                                    <select class="form-control select2" name="id_diklat" required>
                                        <option value="">..Pilih Jenis Diklat..</option>
                                        @foreach ($master_diklat->Data as $item)
                                        <option value="{{ $item->id_diklat }}" {{ $item->id_diklat
                                            ==
                                            $diklat->id_diklat ? 'selected' : '' }}>
                                            {{ $item->jenis_diklat }} -
                                            {{ $item->sub_jenis_diklat }} -
                                            {{ $item->rumpun_diklat }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Diklat</label>
                                    <select class="form-control select2" name="jenis_diklat" id="jenis_diklat" required>
                                        <option value="{{ $jenis_diklat }}" selected>{{ $jenis_diklat }}</option>
                                    </select>
                                    <input type="hidden" name="jenis_diklat" value="{{ $jenis_diklat }}" id="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sub Jenis Diklat</label>
                                    <select class="form-control select2" name="sub_jenis_diklat" id="sub_jenis_diklat"
                                        required>
                                        <option value="">..Sub Jenis Diklat..</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nama Diklat</label>
                                    <input name="nama_diklat" value="{{ $diklat->nama_diklat }}" type="search"
                                        placeholder="..Nama Diklat.." class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat</label>
                                    <input name="tempat_diklat" value="{{ $diklat->tempat_diklat }}" type="search"
                                        placeholder="..Tempat Bang Kom.." class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Penyelenggara Diklat</label>
                                    <input name="penyelenggara" value="{{ $diklat->penyelenggara_diklat }}"
                                        type="search" placeholder="..Penyelenggara Bang Kom.." class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lama Pelatihan (JP)</label>
                                    <input type="number" name="lama_pendidikan" value="{{ $diklat->lama_pendidikan }}"
                                        placeholder="..Jam Pelajaran.." class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Angkatan</label>
                                    <input type="search" name="tahun_angkatan" value="{{ $diklat->tahun_angkatan }}"
                                        placeholder="..Angkatan.." class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="tahun_mulai"
                                        value="{{ Carbon\Carbon::parse($diklat->tahun_mulai)->format('Y-m-d') }}"
                                        placeholder="..Tangga Mulai.." class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="tahun_selesai"
                                        value="{{ Carbon\Carbon::parse($diklat->tahun_selesai)->format('Y-m-d') }}"
                                        placeholder="..Tanggal Selesai.." class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Sertifikat</label>
                                    <input name="no_sertifikat" value="{{ $diklat->nomor_sttpp }}" type="text"
                                        placeholder="..Nomor Sertifikat.." class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Sertifikat</label>
                                    <input type="date" name="tgl_sttpp"
                                        value="{{ Carbon\Carbon::parse($diklat->tgl_sttpp)->format('Y-m-d') }}"
                                        placeholder="..Tanggal Sertifikat.." class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection

@if (session('error'))
@include('partials.error_alert')
@endif


@push('js')
<script>
    $('#jenis_diklat').change(function() {
            var jenis_diklat = $(this).val();
            if (jenis_diklat) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get/sub-jenis-diklat-asn/') }}/" + jenis_diklat,
                    success: function(res) {
                        if (res) {
                            $("#sub_jenis_diklat").empty();
                            $("#sub_jenis_diklat").append(
                                '<option value="">..Sub Jenis Diklat..</option>');
                            $.each(res, function(key, value) {
                                if (value.sub_jenis_diklat ==
                                    "{{ $diklat->sub_jenis_diklat }}") {
                                    $("#sub_jenis_diklat").append('<option value="' + value
                                        .sub_jenis_diklat + ',' + value.id_diklat + ',' +
                                        value
                                        .id_siasn + ',' + value.sertifikat_siasn + '" selected>' + value
                                        .sub_jenis_diklat + ' </option>');
                                } else {
                                    $("#sub_jenis_diklat").append('<option value="' + value
                                        .sub_jenis_diklat + ',' + value.id_diklat + ',' +
                                        value
                                        .id_siasn + ',' + value.sertifikat_siasn + '">' + value
                                        .sub_jenis_diklat + ' </option>');
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
    if ($('#jenis_diklat').val()) {
            $('#jenis_diklat').trigger('change');
        }
</script>
@endpush