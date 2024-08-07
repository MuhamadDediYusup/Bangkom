@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

<section class="section">
    <div class="section-body">
        <h2 class="section-title">Hi, {{ Auth::user()->user_name }}</h2>
        <p class="section-lead">
            Rangkuman informasi aktivitas anda.
        </p>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Aktivitas Laporan 1 Tahun Terakhir</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="table-2">
                                <thead>
                                    <tr>
                                        <th width="5px">No</th>
                                        <th>Bulan</th>
                                        @can('laporan-create')
                                        <th class="text-center">Tambah</th>
                                        @endcan
                                        @can('laporan-approve')
                                        <th class="text-center">Ubah</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['year'] }} - {{ $item['month'] }}</td>
                                        @can('laporan-create')
                                        <td class="text-center">
                                            {{ $item['create'] }}
                                        </td>
                                        @endcan
                                        @can('laporan-approve')
                                        <td class="text-center">
                                            {{ $item['edit'] }}
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold text-center">
                                        <td colspan="2">Total</td>
                                        @can('laporan-create')
                                        <td>
                                            {{ $totalCreate }}
                                        </td>
                                        @endcan
                                        @can('laporan-approve')
                                        <td>
                                            {{ $totalEdit }}
                                        </td>
                                    </tr>
                                    @endcan
                                </tfoot>
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