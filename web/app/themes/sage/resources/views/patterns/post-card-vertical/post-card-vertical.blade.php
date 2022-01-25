{{--
	This element expects:
		$post: {arr}
		$post_type: {str} - post type
		$title: {str} - post title
		$link: {str} - post permalink
		$date: {date} - this is different based on post types, fallback to post creation date
		$author: {str} - this is different based on post type, fallback to post author
		$excerpt: {arr} - this is different based on post type, fallback to post excerpt
		$image: {arr}
And optionally@php
		$location: {str} - events only
@endphp
--}}
@php

$post_type = $post->post_type;
$title = $post->post_title;
$link = get_the_permalink($post);

// date
$date = isset($date) ? new DateTime(strtok($date, " ")) : new DateTime(strtok($post->post_date, " "));
$formattedDate = $date->format('M d, Y');

// author
$author = get_the_author_meta('display_name', $post->post_author);

// excerpt
$excerpt = get_the_excerpt($post) ? \App\limit_text(get_the_excerpt($post), 20) : \App\limit_text($post->content, 20);

// featured image
$image = get_post_thumbnail_id( $post->ID );
$featured_image = \App\responsive_image([
	'image_id' => $image,
	'lazy_load' => false,
	'class' => 'post-card--vertical__image'
]);

@endphp

<a
	class="post-card--vertical__wrapper"
	href="{{ $link }}" aria-label="Read more about {{ $title }}"
>
	<article class="post-card--vertical">
		@include('patterns.image.image', $featured_image)

		<div class="post-card--vertical__content">
			<div class="post-card--vertical__text">
				<div class="post-card--vertical__info">
					@if($post->post_type === 'team')
						<p style="border-right: none; padding: 0;">Team</p>
					@elseif($post->post_type !== 'news' && $post->post_type !== 'post')
							<p style="border-right: none; padding: 0;">{{ ucfirst(substr($post->post_type, 0, -1)) }}</p>
					@elseif($post->post_type === 'news')
						<p style="border-right: none; padding: 0;">News</p>
					@else
						<p style="border-right: none; padding: 0;">Blog</p>
					@endif
				</div>
				<h3 class="post-card--vertical__title">{{ $title }}</h3>
				<div class="post-card--vertical__copy">{!! $excerpt !!}</div>
			</div>
			<span class="post-card--vertical__link" aria-label="Read more about {{ $title }}">Read more</span>
		</div>
	</article>
</a>