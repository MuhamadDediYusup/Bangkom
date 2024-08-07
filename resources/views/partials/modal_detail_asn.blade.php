<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl shadow-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-center" id="exampleModalLongTitle">Kompetensi ASN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <hr>
                <h6>Data Simpeg - Identitas ASN</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="table-2">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td id="nama_lengkap"></td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td id="nip"></td>
                            </tr>
                            <tr>
                                <td>Lahir</td>
                                <td>:</td>
                                <td id="lahir">
                                </td>
                            </tr>
                            <tr>
                                <td>Pangkat</td>
                                <td>:</td>
                                <td id="pangkat"></td>
                            </tr>
                            <tr>
                                <td>Pendidikan</td>
                                <td>:</td>
                                <td id="pendidikan"></td>
                            </tr>
                            {{-- <tr>
                                <td>Sekolah</td>
                                <td>:</td>
                                <td id="sekolah"></td>
                            </tr> --}}
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td id="jabatan"></td>
                            </tr>
                            <tr>
                                <td>Perangkat Daerah</td>
                                <td>:</td>
                                <td id="p_daerah"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>
                <h6>Bang Kom (JP)</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-3">
                        <thead>
                            <tr>
                                <th class="text-center">Bang Kom</th>
                                <th class="text-center">JP Manajerial</th>
                                <th class="text-center">JP Teknis</th>
                                <th class="text-center">JP Fungsional</th>
                                <th class="text-center">JP Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Tahun {{ Carbon\Carbon::now()->year }}</td>
                                <td class="text-center" id="jp_manajerial"></td>
                                <td class="text-center" id="jp_teknis"></td>
                                <td class="text-center" id="jp_fungsional"></td>
                                <td class="text-center fw-bold" id="jp_total"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>
                <h6>Data Simpeg - Kompetensi ASN</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-3" id="table-2">
                        <thead>
                            <tr>
                                <th class="text-center">Jenis Diklat</th>
                                <th class="text-center">Nama Diklat</th>
                                <th class="text-center">JP</th>
                                <th style="width: 20%" class="text-center">Tanggal STTPP</th>
                            </tr>
                        </thead>
                        <tbody id="data-kompetensi">
                        </tbody>
                    </table>
                </div>


                <hr>
                <h6>Usulan Bang Kom</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-3" id="table-2">
                        <thead>
                            <tr>
                                <th class="text-center">Jenis Diklat</th>
                                <th>Sub Jenis Diklat</th>
                                <th>Nama Diklat</th>
                                <th class="text-center">Usulan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="data-form-usulan">
                        </tbody>
                    </table>
                </div>

                <hr>
                <h6>Pengiriman Bang Kom</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-3" id="table-2">
                        <thead>
                            <tr>
                                <th class="text-center">Jenis Diklat</th>
                                <th>Sub Jenis Diklat</th>
                                <th>Nama Diklat</th>
                                <th>Penyelenggara</th>
                                <th class="text-center">SPT</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="data-form-pengiriman">
                        </tbody>
                    </table>
                </div>


                <hr>
                <h6>Laporan Bang Kom</h6>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-3" id="table-2">
                        <thead>
                            <tr>
                                <th class="text-center">Jenis Diklat</th>
                                <th>Sub Jenis Diklat</th>
                                <th>Nama Diklat</th>
                                <th class="text-center">JP</th>
                                <th class="text-center">STTPP</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="data-form-laporan">
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                @can('kompetensi-asn-edit', 'kompetensi-asn-delete')
                    <a href="{{ route('kompetensiasn.index') }}" id="edit-kompetensi-asn" class="btn btn-primary">Edit Data
                        Kompetensi
                        ASN</a>
                @endcan
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
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

<script src="{{ asset('assets/js/request-page/modal-kompetensi-asn.js') }}"></script>
