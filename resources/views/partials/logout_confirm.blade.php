<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
                $('.logout').click(function (e) {
                        e.preventDefault();
                        swal({
                                title: "Apakah Anda akan Logout ?",
                                text: "Anda akan keluar dari Aplikasi",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                        }).then((willDelete) => {
                                if (willDelete) {
                                        $('#logout-submit1').submit();
                                } else {
                                        swal("Anda masih di Aplikasi");
                                }
                        });
                });
        });
</script>