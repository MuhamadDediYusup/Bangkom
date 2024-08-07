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
                            <h4>{{ $perangkat_daerah->Title }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-2">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            ID Perangkat Daerah
                                        </th>
                                        <th>Perangkat Daerah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perangkat_daerah->Data as $item)
                                    <tr>
                                        <td class="text-center"> {{ $item->id_perangkat_daerah }}</td>
                                        <td>
                                            {{ $item->perangkat_daerah }}
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