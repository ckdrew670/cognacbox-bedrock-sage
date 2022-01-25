{{--
  Title: Video Block
  Description: A block with title, text and video
  Category: common
  Icon: video-alt3
  Keywords: video, video with text
  Mode: auto
  Align: left
  SupportsAlign: false
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$title = get_field('title');
	$copy = get_field('copy');
	$videoEmbedURL = get_field('video_embed_URL');
@endphp
<section class='video-block panel'
>
	<div class="video-block__container container">
		@if($title)
		<h2 class="video-block__title text--dark">{{$title}}</h2>
		@endif
		<div v-on:click='handleToggle' class="toggle-button video-block__video-container">
			<div class="play-button-overlay" v-if='!toggled'>
			</div>
			<button class="play-button" v-if='!toggled'>
				<img v-if='!toggled' src="{{get_stylesheet_directory_uri()}}/assets/images/icons/play-button.svg" alt="play video"/>
			</button>
			<div class='youtube-responsive-container video-block__video'>
				@if(!is_admin())
					<iframe
						v-if='toggled'
						v-cloak
						title="{{ $title }}"
						width="800"
						height="500"
						src='{{ $videoEmbedURL.'?autoplay=1' }}'
						allow='autoplay; encrypted-media'>
					</iframe>
				@endif
				<iframe
					v-if='!toggled'
					title="{{ $title }}"
					width="800"
					height="500"
					src='{{ $videoEmbedURL }}'
					allow='autoplay; encrypted-media'>
				</iframe>
			</div>
		</div>
		@if($copy)
		<div class="video-block__copy text--dark">{!! $copy !!}</div>
		@endif
	</div>
</section>