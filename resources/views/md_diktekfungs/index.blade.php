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
                            <h4>{{ $diktekfungs->Title }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2" id="table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID Jenis Diklat</th>
                                        <th class="text-center">Jenis Diklat</th>
                                        {{-- <th class="text-center">ID SAPK Jenis Kursus</th> --}}
                                        <th class="text-center">ID SIASN Jenis Kursus Sertifikat</th>
                                        <th class="text-center">ID SIASN Jenis Kursus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diktekfungs->Data as $item)
                                    <tr>
                                        <td class="text-center"> {{ $item->id_jenis_diktekfungs }}</td>
                                        <td>{{ $item->jenis_diktekfungs }}</td>
                                        {{-- <td class="text-center">{{ $item->sapk_jenis_kursus }}</td> --}}
                                        <td class="text-center">{{ $item->siasn_jenis_kursus_sertifikat }}</td>
                                        <td class="text-center">{{ $item->siasn_jenis_kursus_id }}</td>
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