@extends('layout')

@section('content')
<div class="col-sm-8 blog-main">	
	<h2>Admin</h2>	
	<hr>
	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Location</th>
			<th>Account Created on</th>
			<th>User Type</th>
		</tr>
		@foreach($users as $user)
		<tr>
			<td>{{$user->name}}</td>
			<td>{{$user->email}}</td>
			<td>{{$user->location}}</td>
			<td>{{ Carbon\Carbon::parse($user->created_at)->toFormattedDateString()}}</td>
			<td>
				@if($user->type == 'admin')
					Admin
				@else
					Default User
				@endif
			</td>
		</tr>
		@endforeach
	</table>
</div>
@endsection