{{--
  Title: Two Column Full Width
  Description: A full-width, two-column block with text and image
  Category: common
  Icon: columns
  Keywords: two column, image, text, split screen
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
	$image = \App\responsive_image([
		'image_id' => get_field('image')['id'],
		'lazy_load' => false,
		'class' => 'two-col-full-width__image'
	]);
	$link = get_field('link');
	$alignment = get_field('alignment');

	$bg = $bg_color ? 'background--' . $bg_color : 'background--white';

	$title_color = $bg_color === 'dark' ? 'mid' : 'dark';
	$text_color = $bg_color === 'dark' ? 'white' : 'dark';
	$button_color = $bg_color === 'mid' ? 'dark' : 'mid';
@endphp


<section class="two-col-full-width">
	<div class="two-col-full-width__container two-col-full-width__container--{{$alignment}} {{ $bg }}">
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
				'type' =>  'link'
			])
			@endif
		</div>

		@isset($image)
		@include('patterns.image.image', $image)
		@endisset

	</div>
</section>