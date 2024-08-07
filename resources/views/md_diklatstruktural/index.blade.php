@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

{{--
<?php dd($diklat_struktural) ?> --}}

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-8">
                            <h4>{{ $diklat_struktural->Title }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            ID Diklat
                                        </th>
                                        <th>Nama Diklat</th>
                                        <th class="text-center">SIASN ID Diklat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diklat_struktural->Data as $item)
                                    <tr>
                                        <td class="text-center"> {{ $item->id_diklat }}</td>
                                        <td>
                                            {{ $item->nama_diklat }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->siasn_id_diklat }}
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