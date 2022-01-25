{{--
  Title: Hero
  Description: Hero block
  Category: common
  Icon: align-full-width
  Keywords: hero, homepage
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$type = get_field('type');
	$title = get_field('title');
	$subtitle = get_field('subtitle');
	$copy = get_field('text');
	$text_color = get_field('text_color') ? get_field('text_color') : 'white';
	$bg_type = get_field('background') ? get_field('background') : 'color';
	$background_filter = get_field('image_filter') ? get_field('image_filter') : '';
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
	$bg_color = get_field('bg_color') ? get_field('bg_color') : 'dark';
	$has_button = get_field('hasButton');
	$cta = get_field('cta') ? get_field('cta') : [];
@endphp

@include('patterns.hero.hero', [
	'type' => $type,
	'title' => $title,
	'subtitle' => isset($subtitle) ? $subtitle : '',
	'copy' => $copy,
	'text_color' => $text_color,
	'background_filter' => $background_filter ? $background_filter : '',
	'bgImageDesktop' => isset($bgImageDesktop) ? $bgImageDesktop : [],
	'bgImageMob' => isset($bgImageMob) ? $bgImageMob : [],
	'bg_color' => $bg_color ? $bg_color : 'dark',
	'hasButton' => $has_button,
	'cta' => $cta ? $cta : [],
	'bg_type' => $bg_type
])