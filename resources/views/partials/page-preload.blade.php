{{-- loading preload --}}
<div class="modal fade" id="preload" tabindex="-1" role="dialog" aria-labelledby="loading" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content opacity-75">
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border text-primary mt-md-2" role="status"></div>
                    <p class="mt-2">Mengambil Data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.menu').click(function() {
        $('#preload').modal({
            backdrop: 'static',
            keyboard: false,
            show: true,
        });
    });
</script>
