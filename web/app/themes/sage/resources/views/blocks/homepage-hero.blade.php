{{--
  Title: Homepage Hero
  Description: Main Homepage Hero block
  Category: common
  Icon: align-full-width
  Keywords: hero, homepage
  Mode: auto
  Align: left
  PostTypes: page
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$copy = get_field('text');
	$text_color = get_field('text_color');
	$background_filter = get_field('image_filter');

	if(isset(get_field('bgImage')['id'])) :
		$bgImageDesktop = \App\responsive_image([
			'image_id' => get_field('bgImage')['id'],
			'lazy_load' => false,
			'class' => 'hero--homepage__bgimage--desktop'
		]);
	endif;
	if(isset(get_field('bgImageMob')['id'])) :
		$bgImageMob = \App\responsive_image([
			'image_id' => get_field('bgImageMob')['id'],
			'lazy_load' => false,
			'class' => 'hero--homepage__bgimage--mob'
		]);
	endif;
@endphp

<section class="hero--homepage">
	<div class="hero--homepage__bg" style="filter: brightness({{$background_filter."%"}})">
		@include('patterns.image.image', $bgImageDesktop)
		@include('patterns.image.image', $bgImageMob)
	</div>
	<div class="hero--homepage__content container">
		<h1 class="hero--homepage__title text--{{ $text_color }}">{{ $title }}</h1>
		<div class="hero--homepage__copy text--{{ $text_color }}">{!! $copy !!}</div>
	</div>
</section>