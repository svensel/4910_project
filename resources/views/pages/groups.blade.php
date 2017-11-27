@extends('layouts.general')

<!-- groups table has group_id as table key with person_id and class_id -->
<!-- click group button shows groups and a button to generate schedule for a particular group-->

@section('content')
<!-- modelfinder class app>>>project to pull data from database-->

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="">Groups</a>
        </li>
	<h1>

	</h1>
    </ol>
	<?php
		use App\Project\ModelFinder;
		$groups=ModelFinder::getAuthUserGroups();
		$num_groups=count($groups);	
		$no_groups = false;
		$ret='hell world';
		echo "<div class='row'>
			<div class='col-12'>";
		if($num_groups>0){
			//student belongs 
			$no_groups = true;
			for($i =0; $i < $num_groups; $i++){
				echo "<div class='card text-white bg-primary o-hidden -100'>
					<div class='card-body'>
					</div>
				.$ret.</div>";
			}
		} else {
			$temp = 'no groups';
				echo "	<div class='col-xl-6 col-sm-6 mb-3'>
					<div class='card text-white bg-primary o-hidden -100'>
					<div class='card-body'>
						<div class='card-body-icon'>
							<i class='fa-fw fa-group'>
								$temp
							</i>
						</div>
						<div class='mr-5'>
						</div>
						<a class='card-footer text-white clearfix small z-1' href='#'>
						<span class='float-left'></span>
						<span class='float-right'><i class='fa fa-angle-right'></i></span>
						</a>
					</div></div>
				</div>";
		}	
		echo "</div></div>";
	?>
@endsection
