@extends('master.master')
@section('css')
    <link rel="stylesheet" href="{{asset('css/home.min.css')}}" media="screen"/>
@stop
@section('content')
    <ui-view></ui-view>
@endsection
@section('script')
    <script type='text/javascript' src="{{asset('js/home.min.js')}}"></script>
@stop