@extends('layouts.general')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="">Dashboard</a>
        </li>
    </ol>

    <div class="row">
        <div class="col-12">
            <h1>Dashboard</h1><hr>
            @while($numRows > 0)
                <div class="row">
                    @if($numCourses > 1)
                        @for($i = 0; $i < 2; $i++)
                            <div class="col-xl-6 col-sm-6 mb-3">
                                <div class="card text-white bg-{{$courses[$numCourses-1]['bg_color']}} o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-{{$courses[$numCourses-1]['icon_name']}}"></i>
                                        </div>
                                        <div class="mr-5">{{$courses[--$numCourses]['class_name']}}</div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1" href="#">
                                        <span class="float-left">View Details</span>
                                        <span class="float-right"><i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        @endfor
                    @elseif($numCourses > -1)
                        <div class="col-xl-6 col-sm-6 mb-3">
                            <div class="card text-white bg-{{$courses[$numCourses-1]['bg_color']}} o-hidden h-100">
                                <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-{{$courses[$numCourses-1]['icon_name']}}"></i>
                                    </div>
                                    <div class="mr-5">{{$courses[--$numCourses]['class_name']}}</div>
                                </div>
                                <a class="card-footer text-white clearfix small z-1" href="#">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right"><i class="fa fa-angle-right"></i></span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                @php($numRows--)
            @endwhile
        </div>
    </div>
@endsection