{{--
  Title: Two Column
  Description: A two-column block with text and image
  Category: common
  Icon: columns
  Keywords: two column, image, text
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$copy = get_field('text');
	$image = \App\responsive_image([
		'image_id' => get_field('image')['id'],
		'lazy_load' => false,
		'class' => 'two-column__image'
	]);

	// CTA
	$hasButton = get_field('hasButton');
	$cta = get_field('cta');
	if($hasButton && count($cta) > 0) :
			$cta_background = $cta['class'];
			$cta_type = $cta['type'];
			$cta_title = $cta['text'];

			if($cta_type === 'link') {
				$link = $cta['url'];
			}

			if($cta_type === 'download_button') {
				$file = $cta['download'];
				$link = $file['url'];
			}
	endif;

	$alignment = get_field('alignment');
@endphp


<section class="two-column panel">
	<div class="two-column__container two-column__container--{{$alignment}}">
		<div class="two-column__text-content container">
			@if($title)
				<h2 class="two-column__title text--dark">{{ $title }}</h2>
			@endif
			@if($copy)
				<div class="two-column__text--dark">{!! $copy !!}</div>
			@endif

			@if($hasButton && $cta)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => $cta_background,
				'text' => $cta_title,
				'url' => $link,
				'type' => $cta_type
			])
			@endif
		</div>

		@if($image)
		@include('patterns.image.image', $image)
		@endif

	</div>
</section>