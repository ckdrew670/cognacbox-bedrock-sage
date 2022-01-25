{{--
  Title: CTA Buttons
  Description: CTA Buttons with optional images
  Category: common
  Icon: grid-view
  Keywords: button, icons, grid
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$copy = get_field('copy');
	$variant = get_field('variant');
	$buttons = $variant === 'image' ? get_field('buttons_with_image') : ($variant === 'text' ? get_field('buttons_with_text') : get_field('buttons'));
	$bg_color = get_field('bg_color');
	$bg = $bg_color ? 'background--' . $bg_color : 'background--white';
	$title_color = $bg_color === 'dark' ? 'mid' : 'dark';
	$text_color = $bg_color === 'dark' ? 'white' : 'dark';
@endphp

<section class="cta-buttons-block {{ $bg }}" {{ $variant === 'text' ? 'style=margin-bottom:4rem' : '' }}>
	<div class="cta-buttons-block__container container">
		@if($title || $copy)
		<div class="cta-buttons-block__text-container">
			@if($title)
			<h2 class="cta-buttons-block__title text--{{ $title_color }}">{{ $title }}</h2>
			@endif
			@if($copy)
			<div class="cta-buttons-block__text text--{{ $text_color }}">{!! $copy !!}</div>
			@endif
		</div>
		@endif
		<div class="cta-buttons-block__button-container">
			@if($variant === 'image')
				@foreach ($buttons as $button)
					@php
						if($button['cta']['type'] === 'link') :
							$link = $button['cta']['url'];
						elseif($button['cta']['type'] === 'download_button') :
							$file = $button['cta']['download'];
							$link = $file['url'];
						endif;
					@endphp
					<div class="cta-buttons-block__button-with-image">
						@include('patterns.image.image', \App\responsive_image([
							'image_id' => $button['image']['id'],
							'lazy_load' => false,
							'class' => 'cta-buttons-block__button-with-image__image'
						]))
						<h3 class="cta-buttons-block__button-with-image__title text--{{ $text_color }}">{{ $button['button_copy'] }}</h3>
						@include('patterns.cta-button.cta-button', [
							'bg_color' => $button['cta']['class'],
							'text' => $button['cta']['text'],
							'url' => $link,
							'file' => $button['cta']['download'],
							'type' =>  $button['cta']['type'],
							'addFileInfo' => true
						])
					</div>
				@endforeach
			@endif
			@if($variant === 'text')
				@foreach ($buttons as $button)
					@php
						if($button['cta']['type'] === 'link') :
							$link = $button['cta']['url'];
						elseif($button['cta']['type'] === 'download_button') :
							$file = $button['cta']['download'];
							$link = $file['url'];
						endif;
					@endphp
					<div class="cta-buttons-block__button-with-text">
							@if($button['button_copy'])
								<h3 class="cta-buttons-block__button-with-text__title text--{{ $text_color }}">{{ $button['button_copy'] }}</h3>
							@endif
							@include('patterns.cta-button.cta-button', [
								'bg_color' => $button['cta']['class'],
								'text' => $button['cta']['text'],
								'url' => $link,
								'file' => $button['cta']['download'],
								'type' =>  $button['cta']['type'],
								'addFileInfo' => true
							])
					</div>
				@endforeach
			@endif
			@if($variant === 'button')
				@foreach ($buttons as $button)
					@php
						if($button['cta']['type'] === 'link') :
							$link = $button['cta']['url'];
						elseif($button['cta']['type'] === 'download_button') :
							$file = $button['cta']['download'];
							$link = $file['url'];
						endif;
					@endphp
					<div class="cta-buttons-block__button">
						@include('patterns.cta-button.cta-button', [
							'bg_color' => $button['cta']['class'],
							'text' => $button['cta']['text'],
							'url' => $link,
							'type' =>  $button['cta']['type'],
						])
					</div>
				@endforeach
			@endif
	</div>
</section>


