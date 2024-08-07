$("#status4, #status2, #status1").click(function () {
    if ($("#status4").is(":checked")) {
        $("#alasan").show();
        $("#input_alasan").attr("required", true);
    } else {
        $("#alasan").hide();
        $("#input_alasan").attr("required", false);
    }
});

$(document).ready(function () {
    if ($("#status4").is(":checked")) {
        $("#alasan").show();
        $("#input_alasan").attr("required", true);
    } else {
        $("#alasan").hide();
        $("#input_alasan").attr("required", false);
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
