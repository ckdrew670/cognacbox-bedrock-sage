{{--
  Title: Banner
  Description: Banner block with text and CTA
  Category: common
  Icon: align-full-width
  Keywords: banner, cta
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$copy = get_field('text');
		$link = get_field('link');
@endphp

<section class="banner">
	<div class="banner__contents container">
		<div class="banner__text">{{ $copy }}</div>
		@include('patterns.cta-button.cta-button', [
			'bg_color' => 'mid',
			'text' => $link['title'],
			'url' => $link,
			'type' =>  'link',
		])
	</div>
</section>


