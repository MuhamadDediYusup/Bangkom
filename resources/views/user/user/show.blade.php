@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">Digunakan untuk Ubah Master Data Roles</p>

    <div class="row">
        <div class="col-6 col-md-6 col-lg-6 col-sm-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4></h4>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Username</label>
                                <span class="form-control">{{ $data->user_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>User ID</label>
                                <span class="form-control">{{ $data->user_id }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Role</label>
                                <span class="form-control">{{ $data->getRoleNames()[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-info" href="{{ route('user.index') }}">Kembali</a>
                </div>
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
        <div class="col-6 col-md-6 col-lg-6 col-sm-12">
            <div class="card p-2">
                <div class="card-header">
                    <h4>Detail User</h4>
                </div>
                <div class="profile-widget-description">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="table-2">
                            @if ($profile == null)
                            <tr>
                                <td colspan="3" class="text-center">Data Kosong</td>
                            </tr>
                            @else
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>{{ $profile->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>{{ $profile->nip }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $profile->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td>Satuan Organisasi</td>
                                    <td>:</td>
                                    <td>{{ $profile->satuan_organisasi }}</td>
                                </tr>
                                <tr>
                                    <td>Sub Satuan Organisasi</td>
                                    <td>:</td>
                                    <td>{{ $profile->sub_satuan_organisasi }}</td>
                                </tr>
                                <tr>
                                    <td>Perangkat Daerah</td>
                                    <td>:</td>
                                    <td>{{ $profile->perangkat_daerah }}</td>
                                </tr>
                                <tr>
                                    <td>Role User</td>
                                    <td>:</td>
                                    <td>{{ $data->getRoleNames()[0] }}</td>
                                </tr>
                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
</div>
@endsection