@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Digunakan untuk Ubah Master Data Roles</p>

    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4></h4>
                </div> --}}
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" value="{{ $user->user_name }}" name="user_name"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User ID</label>
                                    <input type="text" value="{{ $user->user_id }}" name="user_id" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Perangkat Daerah</label>
                                    <input type="text"
                                        value="{{ $pegawai->sub_satuan_organisasi }} - {{ $pegawai->satuan_organisasi }} - {{ $pegawai->perangkat_daerah }}"
                                        class="form-control @error('id_perangkat_daerah')
                                    is-invalid
                                    @enderror" readonly>
                                    @error('id_perangkat_daerah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ID Perangkat Daerah</label>
                                    <input type="text" value="{{ $pegawai->id_perangkat_daerah }}"
                                        name="id_perangkat_daerah" class="form-control @error('id_perangkat_daerah')
                                    is-invalid
                                    @enderror" readonly>
                                    @error('id_perangkat_daerah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>ID Perangkat Daerah Tambahan</label>
                                    <input type="text" value="{{ $user->id_perangkat_daerah }}"
                                        name="id_perangkat_daerah" class="form-control @error('id_perangkat_daerah')
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
                                    <select name="roles[]" class="form-control">
                                        <option>--Pilih Role--</option>
                                        @foreach ($role as $item)
                                        <option value="{{ $item->name }}" {{ in_array($item->name,
                                            $user->getRoleNames()->toArray()) ? 'selected="selected"' : '' }}>
                                            {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class=""><span class="text-danger">*</span>Isi Password dan Confirm Password
                            jika ingin merubah password</span>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control @error('password')
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
                                    <input type="password" name="confirm-password" class="form-control @error('confirm-password')
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
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <a class="btn btn-danger" href="{{ route('user.index') }}">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection