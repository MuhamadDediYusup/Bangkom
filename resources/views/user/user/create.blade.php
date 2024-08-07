@extends('layout.main-layout')

@section('content')
    @include('partials.section_header')

    <div class="section-body">
        <h2 class="section-title">{{ $title }}</h2>
        <p class="section-lead">Digunakan untuk menambahkan Master Data Roles</p>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>NIP</label>
                                        <input value="{{ old('user_id') }}" type="text" name="user_id"
                                            class="form-control @error('user_id')
                                        is-invalid
                                    @enderror">

                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" value="{{ old('user_name') }}" name="user_name"
                                            class="form-control @error('user_name')
                                        is-invalid
                                    @enderror">
                                        @error('user_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" value="{{ old('password') }}" name="password"
                                            class="form-control @error('password')
                                        is-invalid
                                    @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" value="{{ old('confirm-password') }}" name="confirm-password"
                                            class="form-control @error('confirm-password')
                                        is-invalid
                                    @enderror">
                                        @error('confirm-password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>ID Perangkat Daerah</label>
                                        <input type="text" value="{{ old('id_perangkat_daerah') }}"
                                            name="id_perangkat_daerah"
                                            class="form-control @error('id_perangkat_daerah')
                                        is-invalid
                                    @enderror">
                                        @error('id_perangkat_daerah')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select name="roles[]"
                                            class="form-control @error('roles')
                                        is-invalid
                                    @enderror"
                                            required>
                                            <option value="">--Pilih Role--</option>
                                            @foreach ($role as $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
@endsection

@if (session('error'))
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
    <script>
        Swal.fire('error', "{{ session('error') }}", 'error');
    </script>
@endif
