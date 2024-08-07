@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Digunakan untuk menambahkan Master Data Permission</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4></h4>
                </div> --}}
                <div class="card-body">
                    <form action="{{ route('permission.update',$data->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Permission</label>
                                    <input type="text" name="permission" value="{{ $data->name }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <a class="btn btn-danger" href="{{ route('permission.index') }}">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection