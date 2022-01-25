{{--
	This element expects

	$background_filter {int}
	$bgImageDesktop {array}
	$bgImageMob {array}
	$text_color {str}
	$title {str}
	$subtitle {str}
	$post_date_timestamp {str}
	$formatted_post_date {str}
	$copy {obj}
	$cta {arr}
	$bg_color {str}
	$type {str}
	$bg_type {str}
--}}
@php
	$title_color = $text_color ? $text_color : ($bg_color === 'dark' ? 'mid' : 'dark');
	$text_color = $text_color ? $text_color : ($bg_color === 'dark' ? 'white' : 'dark');
	$bg = $bg_type === 'color' && $bg_color ? 'background--' . $bg_color : 'background--dark';

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
@endphp
<section class="hero hero__{{ $type }} {{ $bg }}">
	@if($bg_type === 'image')
	<div class="hero__bg" style="filter: brightness({{$background_filter."%"}})">
		@include('patterns.image.image', $bgImageDesktop)
		@include('patterns.image.image', $bgImageMob)
	</div>
	@endif
	<div class="hero__content container">
		<h1 class="hero__title text--{{ $title_color }}">{!!html_entity_decode($title)!!}</h1>
		@if($subtitle)
			<h2 class="hero__subtitle text--{{ $text_color }}">{{ $subtitle }}</h2>
		@endif
		@if(isset($formatted_post_date))
			<div class="hero__publishdate text--{{ $text_color }}">
				Published: <time datetime="{{ $post_date_timestamp }}">{{ $formatted_post_date }}</time>
			</div>
		@endif
		@if(isset($formatted_authors))
			<div class="hero__authorlist text--{{ $text_color }}">
				<p>{{ $formatted_authors }}</p>
			</div>
		@endif
		@if($type === 'page')
			<div class='hero__copy text--{{ $text_color }}'>{!! $copy !!}</div>
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
</section>