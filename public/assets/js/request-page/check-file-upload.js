$(
    "#customFileSertifikat, #customFileSuratLaporan, #customFileSPT, #custumFile, #costumFileSuratPenawaran"
).change(function () {
    var file = this.files[0];
    var fileType = file.type;
    var match = ["application/pdf"];

    if ($(this).attr("id") == "customFileSertifikat") {
        var label = "..PDF Sertifikat..";
    } else if ($(this).attr("id") == "customFileSPT") {
        var label = "..PDF SPT..";
    } else if ($(this).attr("id") == "customFileSuratLaporan") {
        var label = "..PDF Surat Laporan..";
    } else if ($(this).attr("id") == "costumFileSuratPenawaran") {
        var label = "..PDF Surat Permohonan dan Penawaran..";
    } else {
        var label = "..File PDF..";
    }

    if (!(fileType == match[0])) {
        alert("Hanya file PDF yang diizinkan");
        $(
            "#customFileSertifikat, #customFileSuratLaporan, #customFileSPT, #custumFile, #costumFileSuratPenawaran"
        ).val("");
        $(this).next(".custom-file-label").html(label);
        return false;
    }
    if (file.size > 2000000) {
        alert("Ukuran file terlalu besar, ukuran file maksimal 2 MB");
        $(
            "#customFileSertifikat, #customFileSuratLaporan, #customFileSPT, #custumFile, #costumFileSuratPenawaran"
        ).val("");
        $(this).next(".custom-file-label").html(label);
        return false;
    }
});
