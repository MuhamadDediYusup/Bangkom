$("#costumFileSuratPenawaran").on("change", function () {
    var fileName = document.getElementById("costumFileSuratPenawaran").files[0]
        .name;
    $(this).next(".custom-file-label").html(fileName);
});

$(document).ready(function () {
    $(".show-pdf").click(function () {
        var pdf = $(this).data("pdf");
        $("#pdf").attr(
            "src",
            "https://bangkom.slemankab.go.id/petunjuk_file/Rumpun%20Diklat.pdf"
        );
        $("#modal-pdf").modal("show");
    });
});
