@extends('layout.main-layout')

@section('content')
    @include('partials.section_header')

    <div class="section-body">
        <h2 class="section-title">{{ $title }}</h2>
        <p class="section-lead">Digunakan untuk menambahkan Master Data Roles</p>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    {{-- <div class="card-header">
                    <h4></h4>
                </div> --}}
                    <div class="card-body">
                        <form action="{{ route('role.update', $role->id) }}" method="post">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <span class="form-control">{{ $role->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Permission</label>
                                        <br>
                                        @foreach ($permission as $value)
                                            <span class="badge badge-success m-1">{{ $value->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-info" href="{{ route('role.index') }}">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
@endsection
