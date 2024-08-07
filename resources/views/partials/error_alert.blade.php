<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('Gagal', "{!! session('error') !!}", 'error');
    {!! session()->forget('error') !!}
</script>