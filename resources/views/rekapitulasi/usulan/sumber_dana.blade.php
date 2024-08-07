@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<div class="section-body">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <div class="flex-grow-1 bd-highlight">
                                <h4>Rekapitulasi Usulan Berdasarkan Sumber Dana</h4>
                            </div>
                            <div class="bd-highlight">
                                <form action="{{ route('rekapitulasi.usulan') }}" method="GET">
                                    <select name="tahun" id="tahun" class="form-control" onchange="this.form.submit()">
                                        <option value="" {{ $tahunSelected=="" ? 'selected' : '' }}>Pilih Tahun</option>
                                        @foreach ($tahun as $item)
                                        <option value="{{ $item }}" {{ $item==$tahunSelected ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-2" id="table_usulan">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Perangkat Daerah</th>
                                            <th class="text-center text-bold">AKD</th>
                                            <th class="text-center text-bold">HCDP</th>
                                            <th class="text-center text-bold">Penawaran</th>
                                            <th class="text-center text-bold">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>#</b></td>
                                            <td><b>Total</b></td>
                                            <td class="text-center"><b>{{ $total_akd }}</b></td>
                                            <td class="text-center"><b>{{ $total_hcdp }}</b></td>
                                            <td class="text-center"><b>{{ $total_penawaran }}</b></td>
                                            <td class="text-center"><b>{{ $row_count }}</b></td>
                                        </tr>
                                        @foreach ($usulanPerSKPD as $item)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item['nama_perangkat_daerah'] }}</td>
                                            <td class="text-center">{{ $item['akd'] }}</td>
                                            <td class="text-center">{{ $item['hcdp'] }}</td>
                                            <td class="text-center">{{ $item['penawaran'] }}</td>
                                            <td class="text-center"><b>{{ $item['row_count'] }}</b></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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

@push('js')
<script>
    $(document).ready(function() {
            $('#table_usulan').DataTable({
                "bDestroy": true,
                "ordering": false,
                "paging": false,
                // "searching": false,
                "language": {
                    "emptyTable": "Data tidak tersedia"
                },
                // button for export data on bottom of table
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
</script>
@endpush

@push('css')
<style>
    div.dt-buttons {
        position: absolute;
        float: right;
    }
</style>
@endpush