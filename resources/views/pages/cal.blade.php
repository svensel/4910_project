@extends('layouts.general')

@section('content')
    <script src="{{asset('js/daypilot-all.min.js')}}" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="{{asset('css/calendar_green.css')}}" />

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{route('groups')}}">Groups</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">Schedule</a>
        </li>
    </ol>

    <h1>Schedule {{date('M-d-Y')}}</h1><hr>
    <div class='row'>
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="row"><strong>Note: the black line indicates the exact time.</strong></div><br>
            <div class="row">
                <a download style="font-size:20px;" href="{{url('/download/'.$filename)}}" target="_blank">Download spreadsheet of schedule?</a>
            </div><br>
            <div class="row">
                <input id="times" hidden="hidden" title="times" type="text" name="times" value="{{$times}}">
                <div id="dp"></div>
            </div><br><br>
        </div>
    </div>

    <script type="text/javascript">
        var dp = new DayPilot.Calendar("dp");
        dp.theme = 'calendar_green';
        dp.viewType = "Week";
        var i = document.getElementById('times').value;
        dp.events.list = JSON.parse(i);
        dp.heightSpec = "Full";
        dp.eventMoveHandling = "Disabled";
        dp.eventResizeHandling = "Disabled";
        dp.init();
    </script>
@endsection