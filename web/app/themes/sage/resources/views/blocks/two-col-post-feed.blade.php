{{--
  Title: Two Column Post Feed
  Description: A full-width, two-column block with text and post feed
  Category: common
  Icon: columns
  Keywords: two column, post feed, posts, text
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$copy = get_field('text');
	$bg_color = get_field('bg_color');
	$link = get_field('link');
	$alignment = get_field('alignment');
	$posts = get_field('posts');

	$bg = $bg_color ? 'background--' . $bg_color : 'background--white';
	$title_color = $bg_color === 'dark' ? 'mid' : 'dark';
	$text_color = $bg_color === 'dark' ? 'white' : 'dark';
	$button_color = $bg_color === 'mid' ? 'dark' : 'mid';
@endphp


<section class="two-col-full-width">
	<div class="two-col-full-width__container two-col-full-width__container--posts two-col-full-width__container--{{$alignment}} {{ $bg }}">
		<div class="two-col-full-width__text-content container">
			@isset($title)
				<h2 class="two-col-full-width__title two-col-full-width__text--{{ $title_color }}">{{ $title }}</h2>
			@endisset
			@isset($copy)
				<div class="two-col-full-width__text--{{ $text_color }}">{!! $copy !!}</div>
			@endisset

			@if($link)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => $button_color,
				'text' => $link['title'],
				'url' => $link,
				'type' => 'link'
			])
			@endif
		</div>

		<div class="two-col-full-width__post-feed">
			@if(isset($posts) && $posts)
				<ul class="two-col-full-width__posts">
					@foreach ($posts as $post)
						@php
							$event_summary = get_field('event_summary', $post->ID)? get_field('event_summary', $post->ID) : '';
							$event_location = get_field('event_location', $post->ID)? get_field('event_location', $post->ID) : '';
							$position = get_field('position', $post->ID)? get_field('position', $post->ID) : '';
							// dates
							$date = $post->post_date;
							$publication_date = get_field('paper_date', $post->ID)? get_field('paper_date', $post->ID) : '';
							if($post->post_type === 'publications' || $post->post_type === 'research-papers') {
								$date = $publication_date;
							}
							if($post->post_type === 'events') {
								$date = get_field('start_date', $post->ID);
							}
							// authors
							$publication_author = get_field('paper_authors', $post->ID) ? get_field('paper_authors', $post->ID)[0]['author'] : '';
							$blog_author = get_field('author', $post->ID) ? get_field('author', $post->ID) : '';
							if($post->post_type === 'publications' || $post->post_type === 'research-papers') {
								$author = $publication_author;
								if(count(get_field('paper_authors', $post->ID)) > 1) {
									$author = get_field('paper_authors', $post->ID)[0]['author'] . ' et al.';
								}
							}
							if($post->post_type === 'blogs') {
								$author = $blog_author;
							}
						@endphp
						<li class="two-col-full-width__post-feed__list-item">
						@include('patterns.post-card-horizontal.post-card-horizontal', [
							'post' => $post,
							'excerpt' => $event_summary ? $event_summary : null,
							'location' => $event_location,
							'date' => $date,
							'type' => 'list',
							'position' => $position
						])
						</li>
					@endforeach
				</ul>
			@endif
		</div>

	</div>
</section>