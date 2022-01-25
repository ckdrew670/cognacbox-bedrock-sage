{{--
  Title: Text with button
  Description: Text block with optional title and CTA
  Category: common
  Icon: align-full-width
  Keywords: text, text with button, button, cta
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$title = get_field('title');
		$copy = get_field('text');

		// bg colour
		$bg_color = get_field('bg_color');
		$bg = $bg_color ? 'background--' . $bg_color : 'background--white';

		$alignment = get_field('alignment');

		// button
		$cta = get_field('cta');
		if(isset($cta)) :
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
@endphp

<section class="text-with-button panel {{ $bg }}">

	<div class="text-with-button__contents text-with-button__{{ $alignment }} container">
		<div aria-hidden="true" class="text-with-button__contents-border--top" style="background-color: {{ isset($bg_color) && $bg_color !== 'white' ? 'white' : '#3B5C7D'}}"></div>
		@if (isset($title))
			<h2 class="text-with-button__title" style="color: {{ isset($bg_color) && $bg_color === 'dark' ? 'white' : 'inherit'}}">{{ $title }}</h2>
		@endif
		@if (isset($copy))
			<div class="text-with-button__text" style="color: {{ isset($bg_color) && $bg_color === 'dark' ? 'white' : 'inherit'}}">{!! $copy !!}</div>
		@endif

		@if($cta_title)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => $cta_background,
				'text' => $cta_title,
				'url' => $link,
				'type' => $cta_type
			])
		@endif
		<div aria-hidden="true" class="text-with-button__contents-border--bottom" style="background-color: {{ isset($bg_color) && $bg_color !== 'white' ? 'white' : '#3B5C7D'}}"></div>
	</div>
</section>


