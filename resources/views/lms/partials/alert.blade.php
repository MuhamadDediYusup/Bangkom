@if (session('success'))
<script>
    Swal.fire('Berhasil', "{{ session('success') }}", 'success').then(function() {
        fetch("{{ route('session.forget', 'success') }}");
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire('Gagal', "{!! session('error') !!}", 'error').then(function() {
        fetch("{{ route('session.forget', 'error') }}");
    });
</script>
@endif