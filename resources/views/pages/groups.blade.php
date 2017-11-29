@extends('layouts.general')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="">Groups</a>
        </li>
    </ol>
    <h1>Groups</h1><hr>

    <?php
    use App\Project\ModelFinder;
    $groups=ModelFinder::getAuthUserGroups();
    $num_groups=count($groups);
    ?>

    <div class='row'>
        <div class='col-12'>
            <div class="row">
                @if($num_groups>0)
                    @for($i=0; $i < $num_groups; $i++)
                        <form id="{{$groups[$i]->id}}" hidden="hidden" method="POST" action="{{route('scheduleFinder')}}">
                            {{csrf_field()}}
                            <input title="" type="number" hidden="hidden" name="groupId" value="{{$groups[$i]->id}}">
                        </form>
                        <div class='col-xl-6 col-sm-6 mb-3'>
                            <div class='card text-white bg-{{$groups[$i]->course()->get()[0]->bg_color}} o-hidden -100'>
                                <div class='card-body'>
                                    <div class='card-body-icon'><i class='fa fa-fw fa-group'></i></div>
                                    <div class='mr-5'>Group Name: {{$groups[$i]->name}}</div><hr>
                                    <div class="mr-5">Class Name: {{$groups[$i]->course->get()[0]->class_name}}</div><hr>
                                    <div class="mr-5">Members:</div><br>
                                    @foreach($groups[$i]->users()->get() as $user)
                                        <div class="mr-5">{{$user->name}}</div>
                                    @endforeach
                                </div>
                                <a class='card-footer text-white clearfix small z-1' href="javascript:{}" onclick="document.getElementById({{$groups[$i]->id}}).submit()">
                                    <span class='float-left'>Generate schedule?</span>
                                    <span class='float-right'><i class='fa fa-angle-right'></i></span>
                                </a>
                            </div>
                        </div>
                    @endfor
                @else
                    <div class="col-lg-3"><strong>You don't have any groups right now!</strong></div>
                @endif
            </div>
        </div>
    </div>
@endsection
