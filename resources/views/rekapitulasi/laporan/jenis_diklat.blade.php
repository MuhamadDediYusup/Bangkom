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
                                <h4>Rekapitulasi Laporan Berdasarkan Jenis Diklat</h4>
                            </div>
                            <div class="bd-highlight">
                                <form action="{{ route('rekapitulasi.usulan_jenis_diklat') }}" method="GET">
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
                                <table class="table table-striped table-hover table-2" id="table_pegawai_asn">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                No
                                            </th>
                                            <th>Jenis Diklat</th>
                                            <th class="text-center text-bold">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"><b>#</b></td>
                                            <td><b>Total</b></td>
                                            <td class="text-center"><b>{{ $total }}</b></td>
                                        </tr>
                                        @foreach ($laporanPerJenisDiklat as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item['nama_sub_jenis_diklat'] }}</td>
                                            <td class="text-center"><b>{{ $item['row_count'] }}</b></td>
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
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
            $('#table_pegawai_asn').DataTable({
                "bDestroy": true,
                "ordering": false,
                "paging": false,
                // "searching": false,
                "language": {
                    "emptyTable": "Data tidak tersedia"
                },
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
