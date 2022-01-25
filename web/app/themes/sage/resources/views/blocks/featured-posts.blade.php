{{--
  Title: Featured Posts
  Description: A block with up to twelve selected posts
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
	$bg_color = get_field('bg_color');
	$variant = get_field('variant');

	$post_select = get_field('post_select');

	$post_type = get_field('post_type');
	$args = array(
		'numberposts' => get_field('num_of_posts'),
		'order'=> 'DESC',
		'orderby' => 'date',
		'post_type' => $post_type
	);
	$auto_posts = $post_select === 'post_type' ? get_posts( $args ) : false;
	$manual_posts = get_field('posts') ? get_field('posts') : $auto_posts;
	$posts = $auto_posts ? $auto_posts : $manual_posts;

	$bg = $bg_color ? 'background--' . $bg_color : 'background--white';
	$title_color = $bg_color === 'dark' ? 'mid' : 'dark';
	$text_color = $bg_color === 'dark' ? 'white' : 'dark';
	$button_color = $bg_color === 'mid' ? 'dark' : 'mid';
@endphp


@if($variant === 'full')
<section class="featured-posts panel {{ $bg }}">
@elseif($variant === 'split')
<section class="featured-posts featured-posts--split panel {{ $bg }}">
@endif
	<div class="featured-posts__text-content container">
		@if($title)
		<h2 class="featured-posts__title text--{{ $title_color }}">{{ $title }}</h2>
		@endif
		@if($text)
		<div class="featured-posts__text text--{{ $text_color }}">{!! $text !!}</div>
		@endif
	</div>
	<div class="featured-posts__feed container">
		@if($posts)
			<ul class="featured-posts__post-list">
				@foreach ($posts as $post)
				<li class="featured-posts__post-list-item">
					@include('patterns.post-card-vertical.post-card-vertical', [
						'post' => $post
					])
				</li>
				@endforeach
			</ul>
		@endif
		@if($hasButton)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => $button_color,
				'text' => $link['title'],
				'url' => $link,
				'type' =>  'link'
			])
		@endif
	</div>
</section>