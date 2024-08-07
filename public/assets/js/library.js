$(".form-control").on("input", function () {
    // var regex = /<[^>]*>?/gm;
    // regex character <> (less than and greater than) "" (double quote) and ' (single quote)
    var regex = /[<>'"&]/g;
    var str = $(this).val();
    if (str.match(regex)) {
        alert(
            "Maaf.. Anda tidak diperbolehkan menggunakan karakter khusus seperti < > dan ' \" & "
        );
        $(this).val("");
    }
});
