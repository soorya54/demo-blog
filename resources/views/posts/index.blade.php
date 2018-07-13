@extends ('layout')

@section('content')

<div class="col-sm-8 blog-main">		
		@foreach($posts as $post)	
			@if($post->approve == 1)
          		@include('posts.post')
          	@endif
         @endforeach
         {{ $posts->links() }}
</div>
@endsection 