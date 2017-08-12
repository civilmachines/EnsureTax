@extends('master.master')
@section('css')
    <link rel="stylesheet" href="{{asset('css/dashboard.min.css')}}" media="screen"/>
@stop
@section('content')
    <ui-view></ui-view>
@endsection
@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('global.google_map_api')}}&libraries=places"
            type="text/javascript" async></script>
    <script type='text/javascript' src="{{asset('js/dashboard.min.js')}}"></script>
@stop