@extends('layout.main-layout')

@section('content')
@include('partials.section_header')

    <iframe src="{{ route('chat.frame')}}" frameborder="0" style="overflow:hidden;min-height:700px;width:100%" height="100%" width="100%">Your browser isn't compatible</iframe>
@endsection