@extends('layouts.general')

@section('content')
    <script src="{{asset('js/daypilot-all.min.js')}}" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="{{asset('css/calendar_g.css')}}" />

    <input id="times" hidden="hidden" title="times" type="text" name="times" value="{{$times}}">
    <div id="dp"></div>
    <a download href="{{url('/download/'.$filename)}}" target="_blank">Download CSV of Schedule?</a>

    <script type="text/javascript">
        var dp = new DayPilot.Calendar("dp");
        dp.theme = 'calendar_g';
        dp.viewType = "Week";
        var i = document.getElementById('times').value;
        dp.events.list = JSON.parse(i);
        dp.init();
    </script>
@endsection