@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h4>Petunjuk Penggunaan</h4>
                        </div>
                        @can('petunjuk-edit')
                        <div class="p-2 bd-highlight ">
                            <a href="{{ route('pendukung.petunjuk.edit')}}?id_petunjuk={{ $petunjuk->id_petunjuk }}"
                                style="margin-left: auto" class="btn btn-primary">Edit PDF</a>
                        </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div style="height: 600px;">
                            <object data="{{ asset('petunjuk_file') }}/{{ $petunjuk->file_petunjuk }}#navpanes=0"
                                type="application/pdf" width="100%" height="100%">
                                <p>Maaf, File PDF anda tidak bisa ditampilkan.</p>
                            </object>
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
    Swal.fire('Sukses', "{{ session('success') }}", 'success');
</script>
@endif
@endpush