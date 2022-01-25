@php
		// team details for archive card
		$name = get_field('name');
		$position = get_field('position');
		$about = get_field('about');
		$posts = get_posts([
			'post_type' => 'team',
			'numberposts' => 6
		]);
@endphp
@section('content')
	<section class="archive__feed">
		@foreach($posts AS $post)
		@include('patterns.post-card-horizontal.post-card-horizontal', [
			'post' => $post,
			'title' => $name,
			'position' => $position,
			'excerpt' => $about
		])
		@endforeach
	</section>
@endsection