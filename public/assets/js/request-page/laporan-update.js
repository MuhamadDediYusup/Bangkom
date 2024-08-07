$("#status4, #status2, #status1").click(function () {
    if ($("#status4").is(":checked")) {
        $("#alasan").attr("required", true);
        $("#alasan").show();
        $("#text-catatan").html(
            "Alasan Jika Ditolak <span class='text-danger'>*</span>"
        );
    } else if ($("#status2").is(":checked")) {
        $("#alasan").show();
        $("#text-catatan").html("Catatan <span class='text-danger'>*</span>");
    } else {
        $("#alasan").hide();
    }
});

$(document).ready(function () {
    $(".pdf-rumpun-diklat").click(function () {
        var pdf = $(this).data("pdf");
        $("#pdf").attr(
            "src",
            "https://bangkom.slemankab.go.id/petunjuk_file/Rumpun%20Diklat.pdf"
        );
        $("#modal-title").html("Petunjuk Rumpun Diklat");
        $("#modal-pdf").modal("show");
    });
});
$(document).ready(function () {
    $(".pdf-jp").click(function () {
        var pdf = $(this).data("pdf");
        $("#pdf").attr(
            "src",
            "https://bangkom.slemankab.go.id/petunjuk_file/Konversi%20JP.pdf"
        );
        $("#modal-title").html("Petunjuk Pengisian JP");
        $("#modal-pdf").modal("show");
    });
});

$(document).ready(function () {
    $(".pdf-bentuk-jalur").click(function () {
        var pdf = $(this).data("pdf");
        $("#pdf").attr(
            "src",
            "https://bangkom.slemankab.go.id/petunjuk_file/Bentuk%20dan%20Jalur.pdf"
        );
        $("#modal-title").html("Petunjuk Pengisian Sub Jenis Diklat");
        $("#modal-pdf").modal("show");
    });
});
