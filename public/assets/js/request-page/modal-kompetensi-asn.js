$(document).ready(function () {
    $("#edit-kompetensi-asn").on("click", function () {
        $("#loading").modal({
            backdrop: "static",
            keyboard: false,
            show: true,
        });
        $("#modalDetail").modal("hide");
    });

    $(".table").on("draw.dt", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $("table").on("click", ".modal-nip", function () {
        // preload show
        $("#loading").modal({
            backdrop: "static",
            keyboard: false,
            show: true,
        });

        const APP_URL = window.location.origin;

        // Ajax Get Data
        $.get(
            APP_URL + "/getDetailAsn/" + $(this).attr("data-nip"),
            function (data) {
                // Modal Show
                $("#modalDetail").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true,
                });

                // preload hide
                $("#loading").modal("hide");

                // identitas ASN
                $("#nama_lengkap").text(data[0].nama_lengkap);
                $("#nip").text(data[0].nip);
                $("#lahir").text(
                    data[0].lahir_tempat + ", " + data[0].lahir_tanggal
                );
                $("#pangkat").text(data[0].pangkat + ", " + data[0].golru);
                $("#jabatan").text(data[0].jabatan);
                $("#p_daerah").text(
                    data[0].sub_satuan_organisasi +
                        " - " +
                        data[0].satuan_organisasi +
                        " - " +
                        data[0].perangkat_daerah
                );
                $("#pendidikan").text(
                    data[0].pendidikan + " - " + data[0].sekolah
                );
                // $('#sekolah').text( );

                // add link href edit kompetensi to id edit-kompetensi-asn
                $("#edit-kompetensi-asn").attr(
                    "href",
                    APP_URL +
                        "/kompetensi-asn?id_perangkat_daerah=" +
                        data[0].id_perangkat_daerah +
                        "&nama_nip=" +
                        data[0].nip
                );

                // Bangkom JP
                $("#jp_manajerial").text(data[2].jp_manajerial);
                $("#jp_teknis").text(data[2].jp_teknis);
                $("#jp_fungsional").text(data[2].jp_fungsional);
                $("#jp_total").text(data[2].jp_total);

                // Data Kompetensi
                $("#data-kompetensi").empty();
                if (data[1].length > 0) {
                    data[1].forEach((element) => {
                        $("#data-kompetensi").append(
                            '<tr><td class="text-center">' +
                                element.jenis_diklat +
                                "</td><td>" +
                                element.nama_diklat +
                                (element.file != null
                                    ? "<sup data-toggle='tooltip' data-placement='top' title='File STTP Simpeg' data-original-title='File STTPP Simpeg'><a href='https://simpeg.slemankab.go.id" +
                                      element.file +
                                      "' target='_blank' class='text-dark'><span class='text-danger'><b class=''> PDF</b></span></a></sup>"
                                    : "") +
                                (element.id_siasn != null
                                    ? ' <sup><b class="text-primary" data-toggle="tooltip" data-placement="top" title="Data terintegrasi dengan SIASN" data-original-title="Data terintegrasi dengan SIASN">SIASN</b></sup>'
                                    : "") +
                                '</td><td class="text-center">' +
                                element.lama_pendidikan +
                                '</td><td class="text-center">' +
                                element.tgl_sttpp +
                                "</td></tr>"
                        );
                    });
                } else {
                    $("#data-kompetensi").append(
                        '<tr><td colspan="4" class="text-center font-weight-bold text-danger">Tidak Ada Data Kompetensi</td></tr>'
                    );
                }

                // Data Usulan
                $("#data-form-usulan").empty();
                if (data[3].length > 0) {
                    data[3].forEach((element) => {
                        let date = new Date(element.entry_time);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0");
                        let year = date.getFullYear();
                        let formattedDate = day + "-" + month + "-" + year;
                        $("#data-form-usulan").append(
                            '<tr><td class="text-center">' +
                                element.jenis_diklat +
                                "</td><td>" +
                                element.sub_jenis_diklat +
                                "</td><td>" +
                                element.nama_diklat +
                                '</td><td class="text-center">' +
                                formattedDate +
                                '</td><td class="text-center">' +
                                (element.status_usulan == "0"
                                    ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="' +
                                      element.alasan +
                                      '">Ditolak</span>'
                                    : "") +
                                (element.status_usulan == "1"
                                    ? '<span class="badge badge-success">Disetujui</span>'
                                    : "") +
                                (element.status_usulan == "9"
                                    ? '<span class="badge badge-dark">Dikirim</span>'
                                    : "") +
                                (element.status_usulan == null
                                    ? '<span class="badge badge-warning">Ditinjau</span>'
                                    : "") +
                                "</td></tr>"
                        );
                    });
                } else {
                    $("#data-form-usulan").append(
                        '<tr><td colspan="5" class="text-center font-weight-bold text-danger">Belum Ada Data Usulan</td></tr>'
                    );
                }

                // Data Pengiriman
                $("#data-form-pengiriman").empty();
                if (data[5].length > 0) {
                    data[5].forEach((element) => {
                        $("#data-form-pengiriman").append(
                            '<tr><td class="text-center">' +
                                element.jenis_diklat +
                                "</td><td>" +
                                element.sub_jenis_diklat +
                                "</td><td>" +
                                element.nama_diklat +
                                "</td><td>" +
                                element.penyelenggara_diklat +
                                '</td><td class="text-center">' +
                                element.tgl_surat +
                                '</td><td class="text-center">' +
                                // (element.status == '0' ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+element.alasan+'">Ditolak</span>' : '') +
                                (element.status == "0"
                                    ? '<span class="badge badge-success">Dikirim</span>'
                                    : "") +
                                (element.status == "1"
                                    ? '<span class="badge badge-dark">Selesai</span>'
                                    : "") +
                                "</td></tr>"
                        );
                    });
                } else {
                    $("#data-form-pengiriman").append(
                        '<tr><td colspan="6" class="text-center font-weight-bold text-danger">Belum Ada Data Pengiriman</td></tr>'
                    );
                }

                // Data Laporan
                $("#data-form-laporan").empty();

                console.log(data[4]);
                if (data[4].length > 0) {
                    data[4].forEach((element) => {
                        let date = new Date(element.tgl_sttpp);
                        let day = date.getDate().toString().padStart(2, "0");
                        let month = (date.getMonth() + 1)
                            .toString()
                            .padStart(2, "0");
                        let year = date.getFullYear();
                        let formattedDate = day + "-" + month + "-" + year;
                        $("#data-form-laporan").append(
                            '<tr><td class="text-center">' +
                                element.jenis_diklat +
                                "</td><td>" +
                                element.sub_jenis_diklat +
                                "</td><td>" +
                                element.nama_diklat +
                                '</td><td class="text-center">' +
                                element.lama_pendidikan +
                                '</td><td class="text-center">' +
                                formattedDate +
                                '</td><td class="text-center">' +
                                (element.status_laporan == "0"
                                    ? '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="' +
                                      element.alasan +
                                      '">Ditolak</span>'
                                    : "") +
                                (element.status_laporan == "1"
                                    ? '<span class="badge badge-success">Disetujui</span>'
                                    : "") +
                                (element.status_laporan == null
                                    ? '<span class="badge badge-warning">Ditinjau</span>'
                                    : "") +
                                (element.status_laporan == "3" ||
                                element.status_laporan == "2"
                                    ? '<span class="badge badge-warning badge-outlined">Diperbaiki</span>'
                                    : "") +
                                "</td></tr>"
                        );
                    });
                } else {
                    $("#data-form-laporan").append(
                        '<tr><td colspan="5" class="text-center font-weight-bold text-danger">Belum Ada Data Laporan</td><tr>'
                    );
                }
            }
        );
    });
});
