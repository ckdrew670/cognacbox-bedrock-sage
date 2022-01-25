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
		$type: {string}
And optionally@php
		$location: {str} - events only
@endphp
--}}

@php

$post_type = isset($post_type) ? $post_type : $post->post_type;
$title = isset($post_title) ? $post_title : $post->post_title;
$link = get_the_permalink($post);
$type = isset($type) ? $type : '';

// date
$date = isset($date) ? new DateTime(strtok($date, " ")) : new DateTime(strtok($post->post_date, " "));
$formattedDate = $date->format('M d, Y');

// author
$author = isset($author) ? $author : get_the_author_meta('display_name', $post->post_author);

// excerpt
$excerpt = isset($excerpt) ? \App\limit_text($excerpt, 24) : ($post->post_excerpt ? \App\limit_text($post->post_excerpt, 24) : \App\limit_text($post->content, 24));

// featured image
$feat_image = isset($image) ? $image : get_post_thumbnail_id( $post->ID );
$featured_image = \App\responsive_image([
	'image_id' => $feat_image,
	'lazy_load' => false,
	'class' => 'post-card--horizontal__image'
]);

// location for events only
$location = isset($location) ? $location : '';

// position for team only
$position = isset($position) ? $position : '';

// info text for a post - this will be different based on the post type
if($post_type === 'events') :
	$left = $formattedDate;
	$right = $location;
else :
	$left = $author;
	$right = $formattedDate;
endif;
@endphp

<a
	class="post-card--horizontal__wrapper"
	href="{{ $link }}" aria-label="Read more about {{ $title }}"
>
	<article class="post-card--horizontal post-card--horizontal--{{$type}}">
		@include('patterns.image.image', $featured_image)
		<div class="post-card--horizontal__content">
			<div class="post-card--horizontal__text">
					<div class="post-card--horizontal__info">
						@if($post_type === 'programmes' || $post_type === 'case-studies' || $post_type === 'projects')
							<p style="border-right: none; padding: 0;">{{ ucfirst(str_replace('-', ' ', $post_type)) }}</p>
						@elseif($post_type === 'team')
							<p style="border-right: none; padding: 0;">{{ $position }}</p>
						@elseif($post_type === 'news')
							<p style="border-right: none; padding: 0;">{{ $right }}</p>
						@else
							@if($left && $right)
								<p class="post-card--horizontal__info--left">{{ $left }}</p>
								<p class="post-card--horizontal__info--right">{{ $right }}</p>
							@elseif($left && $right === '')
								<p style="border-right: none; padding: 0;">{{ $left }}</p>
							@elseif($right && $left === '')
								<p style="border-right: none; padding: 0;">{{ $right }}</p>
							@endif
						@endif
					</div>
				<h3 class="post-card--horizontal__title">{{ $title }}</h3>
				<div class="post-card--horizontal__copy">{!! $excerpt !!}</div>
			</div>
			<span class="post-card--horizontal__link" aria-label="Read more about {{ $title }}">Read more</span>
		</div>
	</article>
</a>