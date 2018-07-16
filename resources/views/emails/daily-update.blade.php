<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	table {
	  border-collapse: collapse;
	  font-family: arial,sans-serif;
	  width: auto;
	}

	td,
	th {
	  border: 1px solid black;
	  text-align: center;
	  padding: 5px;
	}
	</style>
	<title>Daily Update</title>
	<link href="/css/app.css" rel="stylesheet">
</head>
<body>
	<br>
	<h2 style="text-align: center; color: #f55247;">Sample Blog</h2>
	<hr>
	<p>The list of users of blog are listed below.</p>
	<br>
	<div class="col-sm-8 blog-main">	
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
				<td>{{$user->created_at}}</td>
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
	<br>
	<hr>
	<p>Thanks</p>
	<p>Sample Blog</p>
</body>
</html>