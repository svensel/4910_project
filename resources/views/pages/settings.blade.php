@extends('layouts.general')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Settings</a>
        </li>
    </ol>

    <div class="row">
        <div class="col-12">
            <h1>Settings</h1><hr>
            <ul>
                <li>
                    <strong style="color:red">If you disallow access to Google (TM) Calendars here, be sure
                        to ALSO disallow access in your Google (TM) Account settings if you plan on re-allowing
                        this program's access to your Google (TM) Calendar information!
                    </strong>
                </li>
                <li>
                    <strong style="color:red">If you disallowed access in your Google (TM) Account Settings, but this page is showing
                        that you still have access, disallow access here too!
                    </strong>
                </li>
                <li>
                    <strong>If you are having trouble getting the calendars to sync, disallow access here and on your Google (TM) account, then try re-allowing access here.</strong>
                </li>
            </ul>
            <hr>
            <form id="gcalForm" method="POST" action="{{route('allowGcal')}}">
                {{csrf_field()}}
                <input title="Google Calendar Access" form="gcalForm" type="checkbox" name="allowAccess"
                       @if($user->google_cal_access == true)checked="checked"@endif>
                Allow access to Google (TM) Calendars for schedule creation? @if($user->google_cal_access)<strong>(Currently Allowed)</strong>@endif @if(!$user->google_cal_access)<strong>(Currently Disallowed)</strong>@endif <br><br>
                <button class="btn btn-primary" type="submit">Submit changes</button>
            </form>
        </div>

    </div>
@endsection