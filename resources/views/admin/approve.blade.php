@extends('layout')

@section('content')
<div class="col-sm-8 blog-main">	
	<h2>Post Approval</h2>
	<hr>
	<table>
		<tr>
			<th>Post Title</th>
			<th>User Name</th>
			<th>Created on</th>
		</tr>
		@foreach($posts as $post)
		@if($post->approve == 0)
			<tr>
				<td><a href="/posts/{{$post->id}}">{{$post->title}}</a></td>
				<td>{{$post->user->name}}</td>
				<td>{{$post->created_at->toFormattedDateString()}}</td>
			</tr>
		@endif
		@endforeach
	</table>
</div>
@endsection