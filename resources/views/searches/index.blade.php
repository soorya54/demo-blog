@extends('layout')

@section('content')
<div class="col-sm-8 blog-main">
	<div class="blog-header">
		<h1 class="blog-title">Searching for "{{ $s }}"</h1>
		<p>We've found {{ $posts->count() }} results for your search term in all blog entries</p>
	</div>
    @if( $posts->count() )
        @foreach($posts as $post)   
            @if($post->approve == 1)
            @include('posts.post')
            @endif
        @endforeach
    @else

        <p>No post martch on your term <strong>{{ $s }}</strong></p>

    @endif
</div>

@endsection
