@if($variant === 'full')
<section class="featured-posts panel {{ $bg }}">
@elseif($variant === 'split')
<section class="featured-posts featured-posts--split panel {{ $bg }}">
@endif
	<div class="featured-posts__text-content container">
		@if($title)
		<h2 class="featured-posts__title text--{{ $title_color }}">{{ $title }}</h2>
		@endif
		@if($text)
		<div class="featured-posts__text text--{{ $text_color }}">{!! $text !!}</div>
		@endif
	</div>
	<div class="featured-posts__feed container">
		@if($posts)
			<ul class="featured-posts__post-list">
				@foreach ($posts as $post)
				<li class="featured-posts__post-list-item">
					@include('patterns.post-card-vertical.post-card-vertical', [
						'post' => $post
					])
				</li>
				@endforeach
			</ul>
		@endif
		@if($hasButton)
			@include('patterns.cta-button.cta-button', [
				'bg_color' => $button_color,
				'text' => $link_title,
				'url' => $link,
				'type' =>  'link'
			])
		@endif
	</div>
</section>