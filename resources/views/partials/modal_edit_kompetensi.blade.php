<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg shadow-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-center" id="exampleModalLongTitle">Edit Kompetensi ASN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('usulan_bangkom.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input name="nama" type="text" value="{{ $diklat->nama }}" class="form-control"
                                    readonly>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIP</label>
                                <input name="nip" type="text" class="form-control" value="" id="nip" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Diklat</label>
                                <input name="nama_diklat" value="" id="nama_diklat" type="search"
                                    placeholder="..Nama Diklat.." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Diklat</label>
                                <select class="form-control select2" name="jenis_diklat" required>
                                    <option value="">..Pilih Jenis Diklat..</option>
                                    @foreach ($master_diklat->Data as $item)
                                    <option value="{{ $item->id_diklat }}" {{ $item->id_diklat
                                        ==
                                        $diklat->id_jenis_diktekfungs ? 'selected' : '' }}>
                                        {{ $item->jenis_diklat }} -
                                        {{ $item->sub_jenis_diklat }} -
                                        {{ $item->rumpun_diklat }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tempat</label>
                                <input name="tempat_diklat" value="" id="tempat_diklat" type="search"
                                    placeholder="..Tempat Bang Kom.." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Penyelenggara Diklat</label>
                                <input name="penyelenggara" value="" id="penyelenggara_diklat" type="search"
                                    placeholder="..Penyelenggara Bang Kom.." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tahun_mulai" value="" id="tahun_mulai"
                                    placeholder="..Tangga Mulai.." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tahun_selesai" value="" id="tahun_selesai"
                                    placeholder="..Tanggal Selesai.." class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Sertifikat</label>
                                <input name="no_sertifikat" value="" id="nomor_sertifikat" type="text"
                                    placeholder="..Nomor Sertifikat.." class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Sertifikat</label>
                                <input type="date" name="tgl_sttpp" value="" id="tanggal_sertifikat"
                                    placeholder="..Tanggal Sertifikat.." class="form-control">
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>

    {{-- loading preload --}}
    <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="loading" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary mt-md-2" role="status"></div>
                        <p class="mt-2">Mengambil Data Simpeg...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('table').on('click', '.edit_diklat', function () {
                // preload show
                $('#loading').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });

                var APP_URL = {!! json_encode(url('/')) !!}

            // Ajax Get Data
            $.get(APP_URL +'/getDetailAsn/' + $(this).attr('data-nip'), function (data) {
                // Modal Show
                $('#modalDetail').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });

                // preload hide
                $('#loading').modal('hide');

                // identitas ASN
                $('#nama_lengkap').text(data[0].nama_lengkap);
                $('#nip').text(data[0].nip);
                $('#lahir').text(data[0].lahir_tempat + ', ' + data[0].lahir_tanggal);
                $('#pangkat').text(data[0].pangkat + ', ' + data[0].golru);
                $('#jabatan').text(data[0].jabatan);
                $('#p_daerah').text(data[0].sub_satuan_organisasi + ' - ' + data[0].satuan_organisasi + ' - ' + data[0].perangkat_daerah );
                $('#pendidikan').text(data[0].pendidikan );
                $('#tgl_ijazah').text(data[0].ijazah_tgl );

                // Bangkom JP
                $('#jp_manajerial').text(data[2].jp_manajerial);
                $('#jp_teknis').text(data[2].jp_teknis);
                $('#jp_fungsional').text(data[2].jp_fungsional);
                $('#jp_total').text(data[2].jp_total);

                // Data Kompetensi
                $('#data-kompetensi').empty();
                if (data[1].length > 0) {
                data[1].forEach(element => {
                    $('#data-kompetensi').append('<tr><td class="text-center">'+element.jenis_diklat+'</td><td>'+element.nama_diklat+'</td><td>'+element.lama_pendidikan+'</td><td class="text-center">'+element.tgl_sttpp+'</td></tr>');
                });
                } else {
                    $('#data-kompetensi').append('<tr><td colspan="4" class="text-center font-weight-bold text-danger">Tidak Ada Data Kompetensi</td></tr>');
                }

                // Data Usulan
                $('#data-form-usulan').empty();
                if (data[3].length > 0) {
                data[3].forEach(element => {
                    $('#data-form-usulan').append('<tr><td>'+element.jenis_diklat+'</td><td>'+element.sub_jenis_diklat+'</td><td>'+element.rumpun_diklat+'</td><td>'+element.nama_diklat+'</td><td>'+
                            (element.status == '0' ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+element.alasan+'">Ditolak</span>' : '') +
                            (element.status == '1' ? '<span class="badge badge-success">Disetujui</span>' : '') +
                            (element.status == '9' ? '<span class="badge badge-warning">Ditinjau</span>' : '')
                        +'</td></tr>');
                });
                } else {
                    $('#data-form-usulan').append('<tr><td colspan="5" class="text-center font-weight-bold text-danger">Belum Ada Data Usulan</td></tr>');
                }

                // Data Usulan
                $('#data-form-pengiriman').empty();
                if (data[5].length > 0) {
                    data[5].forEach(element => {
                    $('#data-form-pengiriman').append('<tr><td>'+element.jenis_diklat+'</td><td>'+element.sub_jenis_diklat+'</td><td>'+element.rumpun_diklat+'</td><td>'+element.nama_diklat+'</td><td>'+
                            // (element.status == '0' ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+element.alasan+'">Ditolak</span>' : '') +
                            (element.status == '0' ? '<span class="badge badge-success">Dikirim</span>' : '') +
                            (element.status == '1' ? '<span class="badge badge-dark">Selesai</span>' : '')
                        +'</td></tr>');
                });
                } else {
                    $('#data-form-pengiriman').append('<tr><td colspan="5" class="text-center font-weight-bold text-danger">Belum Ada Data Usulan</td></tr>');
                }

                // Data Laporan
                $('#data-form-laporan').empty();
               if (data[4].length > 0) {
                    data[4].forEach(element => {
                        $('#data-form-laporan').append('<tr><td>'+element.jenis_diklat+'</td><td>'+element.sub_jenis_diklat+'</td><td>'+element.rumpun_diklat+'</td><td>'+element.nama_diklat+'</td><td>'+
                            (element.status == '0' ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+element.alasan+'">Ditolak</span>' : '') +
                            (element.status == '1' ? '<span class="badge badge-success">Disetujui</span>' : '') +
                            (element.status == '9' ? '<span class="badge badge-warning">Ditinjau</span>' : '')
                        +'</td></tr>')
                    } );
               } else {
                    $('#data-form-laporan').append('<tr><td colspan="5" class="text-center font-weight-bold text-danger">Belum Ada Data Laporan</td><tr>');
               }
            });
            });
        });
    </script>