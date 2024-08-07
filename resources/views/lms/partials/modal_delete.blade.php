@push('css')
<style>
    .icon-exclamation {
        color: rgba(255, 15, 15, 0.5);
        font-size: 10rem;
    }
</style>
@endpush

<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mb-3">
                    <span class="icon-exclamation"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <h4 class="fw-bold">Apakah anda yakin akan menghapus <span id="text-item-delete"></span> ini?</h4>
                    <p class="mb-4"><span id="sub-item-delete-text">Anda tidak dapat mengembalikan ini!</span></p>
                    <form id="form-delete" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <span id="method"></span>
                        <button type="submit" class="btn btn-primary">Hapus</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>