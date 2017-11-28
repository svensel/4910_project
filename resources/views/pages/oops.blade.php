@extends('layouts.general')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{route('settings')}}">Settings</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">OOPS!</a>
        </li>
    </ol>

    <div class="row">
        <div class="col-12">
            <h1>OOPS! Try this!</h1><br>
            <strong style="color:red">Adding Google (TM) Calendar access failed!</strong><br><br>
            <strong>If you disallow access to Google (TM) Calendars, be sure
                to ALSO disallow access in your Google (TM) Account settings if you plan on re-allowing
                this program's access to your Google (TM) Calendar information!</strong><br><br>
        </div>

    </div>
@endsection