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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Perangkat Daerah</th>
                                        <th class="text-center">Visit</th>
                                        <th class="text-center">Status</th>
                                        <th width="12%" class="text-center">Tanggal</th>
                                        <th class="text-center">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $item)
                                    <tr>
                                        <td class="text-center"> {{ $key++ + 1 }}</td>
                                        <td data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="{{ $item->jabatan }}">
                                            @empty($item->nama_lengkap)
                                            {{ $item->user_name }}
                                            @else
                                            {{ $item->nama_lengkap }}
                                            @endempty
                                        </td>
                                        <td>
                                            @empty($item->id_perangkat_daerah_master)
                                            <b><span class="text-danger">Pegawai Non Aktif</span></b>
                                            @else
                                            {{ $item->satuan_organisasi }} <br>
                                            {{ $item->perangkat_daerah }}
                                            @endempty
                                        </td>
                                        <td class="text-center">{{ $item->login_count }}</td>
                                        <td class="text-center">{{ $item->session }}</td>
                                        <td class="text-center">
                                            @empty(!$item->login_time)
                                            {{ date('d-m-Y', strtotime($item->login_time)) }}
                                            @else
                                            {{ $item->login_time }}
                                            @endempty
                                        </td>
                                        <td class="text-center">
                                            @empty(!$item->login_time)
                                            {{ date('H:i', strtotime($item->login_time)) }}
                                            @else
                                            {{ $item->login_time }}
                                            @endempty
                                        </td>
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