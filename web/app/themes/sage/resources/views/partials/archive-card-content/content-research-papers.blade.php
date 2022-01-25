@php
	// publication details for archive card
		$authors = [];
		foreach(get_field('paper_authors') as $author) {
			array_push($authors, $author['author']);
		}
		$author = count($authors) > 1 ? (count($authors) > 2 ? $authors[0] : $authors[0] . ' and ' . $authors[1]) : $authors[0];
		$abstract = get_field('paper_abstract');
		$publication_date = get_field('paper_date');
		$posts = get_posts([
			'post_type' => 'research-papers',
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
			'author' => $author,
			'date' => $publication_date,
			'excerpt' => $abstract ? $abstract : '',
		])
		@endforeach
	</section>
@endsection