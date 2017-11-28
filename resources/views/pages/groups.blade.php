@extends('layouts.general')

<!-- groups table has group_id as table key with person_id and class_id -->
<!-- click group button shows groups and a button to generate schedule for a particular group-->

@section('content')
    <!-- modelfinder class app>>>project to pull data from database-->

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="">Groups</a>
        </li>
    </ol>
    <h1>Groups</h1><hr>

    <?php
    use App\Project\ModelFinder;
    //need to get group info from database
    $groups=ModelFinder::getAuthUserGroups();
    $num_groups=count($groups);
    $temp = 'no groups';
    $no_groups = false;
    $ret='hell world';
    ?>
    <div class='row'>
        <div class='col-12'>
            <div class="row">
                @if($num_groups>0)
                    @php($no_groups = true)
                    @for($i=0; $i < $num_groups; $i++)
                        <div class='col-xl-6 col-sm-6 mb-3'>
                            <div class='card text-white bg-primary o-hidden -100'>
                                <div class='card-body'>
                                    <div class='card-body-icon'><i class='fa-fw fa-group'></i></div>
                                    <div class='mr-5'>Name: {{$temp}}</div>

                                </div>
                                <a class='card-footer text-white clearfix small z-1' >
                                    <span class='float-left'>Generate schedule?</span>
                                    <span class='float-right'><i class='fa fa-angle-right'></i></span>
                                </a>
                            </div>
                        </div>
                    @endfor
                @else
                    <div class='col-xl-6 col-sm-6 mb-3'>
                        <div class='card text-white bg-primary o-hidden -100'>
                            <div class='card-body'>
                                <div class='card-body-icon'>
                                    <i class='fa-fw fa-group'></i>
                                </div>
                                <div class='mr-5'>{{$temp}}</div>
                                <a class='card-footer text-white clearfix small z-1' href='fun(grp_id)'>
					<script hidden=hidden>function fun() => { 
						
					} </script>
                                    <span class='float-left'>Generate schedule?</span>
                                    <span class='float-right'><i class='fa fa-angle-right'></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
