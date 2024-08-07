$("#customFileSuratLaporan").on("change", function () {
    var fileName = document.getElementById("customFileSuratLaporan").files[0]
        .name;
    $(this).next(".custom-file-label").html(fileName);
});

$("#customFileSertifikat").on("change", function () {
    var fileName = document.getElementById("customFileSertifikat").files[0]
        .name;
    $(this).next("#constum-file-sertifikat").html(fileName);
});


