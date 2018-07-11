@if (count($errors))
	<div class="form-group">
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
			{{ $error }} <br>
			@endforeach
		</div>
	</div>
@endif