{{--
  Title: Quotation
  Description: Quotation block with optional image
  Category: common
  Icon: format-quote
  Keywords: quotation, quote
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$quote = get_field('text');
		$author = get_field('author');
		$position = get_field('position');
		if(get_field('image')) :
			$image = \App\responsive_image([
				'image_id' => get_field('image')['id'],
				'lazy_load' => false,
				'class' => 'roundel quotation__image'
			]);
		endif;

		$bg_color = get_field('bg_color');
		$text_color = $bg_color === 'dark' ? 'white' : 'dark';
		$bg = $bg_color ? 'background--' . $bg_color : '';
		$quotation_mark = $bg_color === 'mid' ? 'dark' : 'mid';
@endphp

<section class="quotation panel {{ $bg }}">
	<div class="quotation__contents container">
		@if(isset($image))
			@include('patterns.image.image', $image)
		@endif
		<div class="quotation__text-content">
			<div class="quotation__quote">
				<img class="quotation__icon" src="{{ get_stylesheet_directory_uri()}}/assets/images/icons/quotation-{{ $quotation_mark }}.svg" alt="quotation icon"/>
				<div class="quotation__text text--{{ $text_color }}">{!! $quote !!}</div>
			</div>
			@if($author)
			<div class="quotation__author-details">
				<p class="quotation__author text--{{ $text_color }}">{{ $author }}</p>
					@if($position)
					<p class="quotation__position text--{{ $text_color }}">{{ $position }}</p>
					@endif
				@endif
			</div>
		</div>
	</div>
</section>


