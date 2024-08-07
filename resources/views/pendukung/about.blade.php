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
                            <h4>ABANGKOMANDAN</h4>
                        </div>
                        @can('about-edit')
                        <div class="p-2 bd-highlight ">
                            <a href="{{ route('pendukung.about.edit')}}" style="margin-left: auto"
                                class="btn btn-primary">Edit About</a>
                        </div>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div>
                            {!! $about->text_about !!}
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