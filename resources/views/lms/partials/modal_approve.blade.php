@push('css')
<style>
    .icon-exclamation {
        color: rgba(255, 15, 15, 0.5);
        font-size: 10rem;
    }
</style>
@endpush

<div class="modal fade" id="approve_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-3">
                    <span class="display-1 text-primary"><i class="fa-solid fa-circle-info"></i></span>
                    <h4 class="fw-bold"><span id="text-desc"></span></h4>
                    <p class="mb-4"><span id="sub-item-delete-text">
                            Tindakan ini tidak dapat dibatalkan!
                        </span></p>
                    <form id="form-approve" action="" method="POST">
                        @csrf
                        @method('GET')
                        <span id="method"></span>
                        <button type="submit" class="btn btn-primary"><span id="text-approve"></span></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>