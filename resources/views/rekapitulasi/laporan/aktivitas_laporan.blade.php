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
                                        <th width="2%" class="text-center">No</th>
                                        <th width="25%">Nama</th>
                                        <th width="25%">Perangkat Daerah</th>
                                        <th width="16%" class="text-center">Tambah</th>
                                        <th width="16%" class="text-center">Ubah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_lengkap }}</td>
                                        <td>{{ $item->satuan_organisasi }} <br> {{ $item->perangkat_daerah }}</td>
                                        <td class="text-center">{{ $item->totalCreate }}</td>
                                        <td class="text-center">{{ $item->totalEdit }}</td>
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
