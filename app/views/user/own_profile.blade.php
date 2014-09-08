<?php if (isset($mess)) {
	echo $mess;
} ?>
<div class="hero-unit">
	<h3>{{$user->username}}</h3>

	<p> ACTIVE VIDEO</p>
	<div id='active-video'>
		@if(count($active_videos) == 0)
			No active video
		@else
		<table class="table table-bordered">
		  	<tr>
		  		<th>ID</th>
		  		<th>Title</th>	
		  		<th>Deactive</th>
		  		<th>Delete</th>	  
			</tr>
			@for($i = 0; $i < count($active_videos) ; $i++)
			<tr>
		  		<td>{{$active_videos[$i]->id}}</td>
		  		<td>{{ HTML::linkAction('VideoController@showVideo', $active_videos[$i]->title , array($active_videos[$i]->id), array('class' => '')) }}</td>		  
		  		<td>{{ HTML::linkAction('VideoController@deactive', 'Deactive' , array($active_videos[$i]->id), array('class' => 'btn btn-warning')) }}</td>
		  		<td>{{ HTML::linkAction('VideoController@delete', 'Delete' , array($active_videos[$i]->id), array('class' => 'btn btn-danger')) }}</td>
			</tr>
			@endfor
		</table>
		@endif
		
	</div>

	<p> DEACTIVE VIDEO</p>
	<div id='deactive-video'>
		@if(count($deactive_videos) == 0)
					No active video
		@else
		<table class="table table-bordered">
		  	<tr>
		  		<th>ID</th>
		  		<th>Title</th>
		  		<th>Reborn Request</th>
		  		<th>Reborn</th>	
		  		<th>Delete</th>		  
			</tr>
			@for($i = 0; $i < count($deactive_videos) ; $i++)
			<tr>
		  		<td>{{$deactive_videos[$i]->id}}</td>
		  		<td>{{ HTML::linkAction('VideoController@showVideo', $deactive_videos[$i]->title , array($deactive_videos[$i]->id), array('class' => '')) }}</td>
		  		<td><b>{{$deactive_videos[$i]->request_number}}</b> user(s) want this video reborn</td>
		  		<td>{{ HTML::linkAction('VideoController@reborn', 'Reborn' , array($deactive_videos[$i]->id), array('class' => 'btn btn-primary')) }}</td>			  
		  		<td>{{ HTML::linkAction('VideoController@delete', 'Delete' , array($deactive_videos[$i]->id), array('class' => 'btn btn-danger')) }}</td>
			</tr>
			@endfor
		</table>
		@endif
	</div>
</div>


