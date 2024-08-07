@extends('layout.main-layout')

@section('content')
    @include('partials.section_header')

    <div class="section-body">
        <section>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-8">
                                <h4>{{ $title }}</h4>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end">
                                @can('role-create')
                                    <a href="{{ route('permission.create') }}" class="btn btn-primary">Tambah Permission</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-2">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama</th>
                                            @can('role-edit', 'role-delete')
                                                <th class="text-center" style="width: 10%">Action</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permission as $key => $item)
                                            <tr>
                                                <td class="text-center"> {{ $key++ + 1 }}</td>
                                                <td> {{ $item->name }}</td>
                                                @can('role-edit', 'role-delete')
                                                    <td class="text-center">
                                                        @can('role-edit')
                                                            <a href="{{ route('permission.edit', $item->id) }}" class="icon-edit"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-original-title="Edit Permission"><i
                                                                    class="fa-regular fa-pen-to-square"></i></a>
                                                        @endcan
                                                        @can('role-delete')
                                                            <a class="icon-delete px-3" href="javascript:void(0)"
                                                                data-id-delete="{{ $item->id }}" data-toggle="tooltip"
                                                                data-placement="top" title=""
                                                                data-original-title="Hapus Permission">
                                                                <i class="fa-solid fa-trash-can text-danger"></i></a>
                                                        @endcan
                                                    </td>
                                                @endcan
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    @if (session('success'))
        <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
        <script>
            Swal.fire('success', "{{ session('success') }}", 'success');
        </script>
    @endif
    @if (session('error'))
        <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
        <script>
            Swal.fire('error', "{{ session('error') }}", 'error');
        </script>
    @endif

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
                        <p class="mb-4">Anda tidak dapat mengembalikan ini!</p>
                        <form id="form-delete" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Hapus</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.icon-delete').click(function() {
                $('#deletemodal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true,
                });
                var idPermission = $(this).attr('data-id-delete');
                var actionDel = "{{ route('permission.destroy', ':id') }}".replace(':id', idPermission);
                $("#form-delete").attr("action", actionDel);
                $("#text-item-delete").text("Role Permission");
            });
        });
    </script>
@endpush
