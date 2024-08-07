@extends('errors.layout')

@section('content')
<div class="section-header">
    <h1>Halaman Kesalahan</h1>
</div>

<div class="section-body">
    <div class="alert alert-danger" role="alert">
        {{ $exception->getMessage() }}
    </div>
</div>

@endsection
