@php
	// event details for archive card
	$event_start_date = get_field('start_date');
	$event_summary = get_field('event_summary');
	$event_location = get_field('event_location');

	$posts = get_posts([
		'post_type' => 'events',
		'numberposts' => 6
	]);
@endphp

@section('content')
<section class="archive__feed">
	@foreach($posts AS $post)
	@include('patterns.post-card-horizontal.post-card-horizontal', [
		'post_type' => $post->post_type,
		'post' => $post,
		'title' => $post->title,
		'date' => $event_start_date,
		'excerpt' => $event_summary,
		'location' => $event_location
	])
	@endforeach
</section>
@endsection
