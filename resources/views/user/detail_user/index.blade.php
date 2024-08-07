@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<!-- Main Content -->
<section class="section">
    <div class="section-body">
        <h2 class="section-title">Hi, {{ Auth::user()->user_name }}</h2>
        <p class="section-lead">
            Change information about yourself on this page.
        </p>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card p-2">
                    <div class="card-header">
                        <h4>Detail User</h4>
                    </div>
                    <div class="profile-widget-description">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="table-2">
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td>{{ $data->nip }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td>{{ $data->jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Satuan Organisasi</td>
                                        <td>:</td>
                                        <td>{{ $data->satuan_organisasi }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Satuan Organisasi</td>
                                        <td>:</td>
                                        <td>{{ $data->sub_satuan_organisasi }}</td>
                                    </tr>
                                    <tr>
                                        <td>Perangkat Daerah</td>
                                        <td>:</td>
                                        <td>{{ $data->perangkat_daerah }}</td>
                                    </tr>
                                    <tr>
                                        <td>Role User</td>
                                        <td>:</td>
                                        <td>{{ Auth::user()->getRoleNames()->first() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div>
                <div class="card">
                    <form method="POST" action="{{ route('user.updatePassword') }}" class="needs-validation" novalidate="">
                        @csrf
                        <div class="card-header">
                            <h4>Ubah Password</h4> <span><span class="text-danger">*</span>Isi hanya jika ingin mengubah
                                password</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label for="inputPassword5">Password Baru</label>
                                    <input type="password" id="inputPassword5" class="form-control"
                                        aria-describedby="passwordHelpBlock" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label for="inputPassword5_confirm">Konfirmasi Password Baru</label>
                                    <input type="password" id="inputPassword5_confirm" class="form-control"
                                        aria-describedby="passwordHelpBlock" name="password_confirm" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                        {{-- <div class="card-footer text-right"> --}}
                            {{-- <a class="btn btn-danger" href="{{ route('dashboard') }}">Batal</a> --}}
                            {{-- </div> --}}
                    </form>
                </div>
                <div class="card">
                        <div class="card-header">
                            <h4>Login Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="table-2">
                                    <tbody>
                                        <tr>
                                            <td>Jumlah Login</td>
                                            <td>:</td>
                                            <td>{{ Auth::user()->login_count}}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Login</td>
                                            <td>:</td>
                                            <td>{{ Auth::user()->session }}</td>
                                        </tr>
                                        <tr>
                                            <td>Terakhir Login</td>
                                            <td>:</td>
                                            <td>{{ Auth::user()->login_time }}</td>
                                        </tr>
                                        <tr>
                                            <td>Terakhir Logout</td>
                                            <td>:</td>
                                            <td>{{ Auth::user()->logout_time }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@push('css')
@if (session('success'))
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
<script>
    Swal.fire('success', "{{ session('success') }}", 'success');
</script>
@endif

@if ($errors->any())
<div>
    <ul>
        <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
        @foreach ($errors->all() as $error)
        <script>
            Swal.fire('error', "{{ $error }}", 'error');
        </script>
        @endforeach
    </ul>
</div>
@endif
@endpush