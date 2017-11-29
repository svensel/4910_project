@extends('layouts.general')
@section('content')
    <a download href="{{url('/download/'.$filename)}}" target="_blank">Download schedule?</a>
@endsection