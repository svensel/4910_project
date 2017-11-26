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
	<style>
	.card {
		box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
		transition: 0.3s;
		border-raduis: 5px;
	}
	.container{
		border-raduis: 5px 5px 0 0;
	}
	</style>
	<?php
		use App\Project\ModelFinder;
		$groups=ModelFinder::getAuthUserGroups();
		$num_groups=count($groups);	
		$no_groups = false;
		$ret='hell world';
		echo "<div class='container'>";
		if($num_groups>0){
			//student belongs 
			$no_groups = true;
			for($i =0; $i < $num_groups; $i++){
				echo "<div class='card'>.$ret.</div>";
			}
		} else {
			$temp = 'no groups';
			echo "<div class='card'>
				<div class='container'>
					<h4><b>$temp</b></h4>
				</div>
			</div>";
		}	
		echo "</div>";
	?>
@endsection
