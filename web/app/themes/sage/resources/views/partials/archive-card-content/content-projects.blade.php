@php
	$posts = get_posts([
		'post_type' => 'projects',
		'numberposts' => 6
	]);
@endphp

@section('content')
	<section class="archive__feed">
		@foreach($posts AS $post)
		@include('patterns.post-card-horizontal.post-card-horizontal', [
			'post' => $post
		])
		@endforeach
	</section>
@endsection