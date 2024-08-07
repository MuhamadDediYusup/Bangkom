<script>
    function filterCheck() {
        // Function to update the button style
        function updateButtonStyle(session_nama, session_tanggal, session_nama_diklat) {
            if (session_nama != null || session_tanggal != null || session_nama_diklat != null) {
                $('#btn-filter').addClass('text-warning');
            } else {
                $('#btn-filter').removeClass('text-warning');
            }
        }

        // Initial call to updateButtonStyle
        updateButtonStyle();

        // AJAX request to get data session
        $.ajax({
            url: "{{ route('get.session') }}",
            method: "GET",
            contentType: 'application/json',
            success: function(data) {
                session_nama = data.filter_nama_nip;
                session_tanggal = data.filter_tanggal;
                session_nama_diklat = data.filter_nama_diklat;
                updateButtonStyle(session_nama, session_tanggal, session_nama_diklat);
            },
            error: function(error) {
                console.error("Error fetching session data:", error);
                updateButtonStyle();
            }
        });
    }
</script>