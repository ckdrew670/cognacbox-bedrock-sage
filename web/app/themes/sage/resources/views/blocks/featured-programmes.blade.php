{{--
  Title: Four Featured Posts
  Description: A block with four selected posts
  Category: common
  Icon: admin-post
  Keywords: featured, selected posts, posts, post
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$text = get_field('text');
	$posts = get_field('posts');
	$hasButton = get_field('hasButton');
	$link = get_field('link');

	$post_select = get_field('post_select');
	$post_type = get_field('post_type');
	$args = array(
		'num_posts' => 4,
		'order'=> 'DESC',
		'orderby' => 'date',
		'post_type' => $post_type
	);
	$auto_posts = $post_select === 'post_type' ? get_posts( $args ) : false;
	$manual_posts = get_field('posts') ? get_field('posts') : $auto_posts;
	$posts = $auto_posts ? $auto_posts : $manual_posts;
	$num_posts = count($posts);
@endphp

<section class="featured-posts--four panel background--white">
	<div class="featured-posts--four__text-content container">
		@if($title)
		<h2 class="featured-posts--four__title text--dark">{{ $title }}</h2>
		@endif
		@if($text)
		<div class="featured-posts--four__text text--dark">{!! $text !!}</div>
		@endif
	</div>
	<div class="featured-posts--four__feed container">
		@if($posts)
			<ul class="featured-posts--four__post-list {{ $num_posts < 4 ? 'center' : ''}}">
				@foreach ($posts as $post)
				<li class="featured-posts--four__post-list-item">
					@include('patterns.post-card-vertical.post-card-vertical', [
						'post' => $post
					])
				</li>
				@endforeach
			</ul>
		@endif
		@if($hasButton)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => 'dark',
				'text' => $link['title'],
				'url' => $link,
				'type' =>  'link'
			])
		@endif
	</div>
</section>