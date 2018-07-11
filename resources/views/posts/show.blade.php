@extends('layout')

@section('content')
<div class="col-sm-8 blog-main">			
	<h1>{{$post->title}}</h1>
	<hr>
	@if(count($post->tags))
		@foreach($post->tags as $tag)
			<a href="/posts/tags/{{$tag->name}}">
				{{$tag->name}}
			</a>
			&nbsp;
		@endforeach
	@endif
	<hr>
	{{$post->body}}
	<hr>
	<div class="comments">
		<ul class="list-group">
			@foreach($post->comments as $comment)
			<li class="list-group-item">
				<strong>
					{{$comment->created_at->diffForHumans()}} : &nbsp;
				</strong>
				{{$comment->body}}
			</li>
			@endforeach
		</ul>
	</div>
	<hr>
	@if(auth()->check())
	<div class="card">
		<div class="card-block">
			<form method="POST" action="/posts/{{$post->id}}/comments">
				{{csrf_field()}}
				<div class="form-group">
					<textarea name="body" placeholder="Your comment here." class="form-control" required></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Add Comment</button>
				</div>
			</form>
			@include('layouts.errors')
		</div>
	</div>
	@endif
</div>
@endsection