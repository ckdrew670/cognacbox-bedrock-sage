{{--
	This element expects:
	$menu_links: {array}{assoc_array}
		$url: string
		$title: string
	$social_links {array}{assoc_array}
		$url: string
		$social_media_platform: string
	$footer_logo {obj}
	$footer_copyright {string}
--}}

@include('patterns.share-bar.share-bar')

<footer id="footer" class="footer">
	<div class="footer__container container">
		<div class="footer__contents">
			<div class="footer__logo">
				@include('patterns.image.image', $footer_logo)
			</div>
			<div class="footer__navs">
			@if (isset($menu_links))
				<nav class="footer__nav">
					<ul>
					@foreach ($menu_links as $item)
						<li><a href='{{ $item['link']['url'] }}'>{{ $item['link']['title'] }}</a></li>
					@endforeach
					</ul>
				</nav>
			@endif
			@if (isset($social_links))
				<nav class="footer__socials-nav">
					<ul>
					@foreach ($social_links as $item)
						<li class="footer__socials-nav__list-item">
							<a href='{{ $item['url'] }}'><img class="{{strtolower($item['social_media_platform'])}}-icon" src="{{ get_stylesheet_directory_uri()}}/assets/images/icons/{{strtolower($item['social_media_platform'])}}-white.svg" alt="{{strtolower($item['social_media_platform'])}} icon"/>{{ ucfirst($item['social_media_platform']) }}</a>
						</li>
					@endforeach
					</ul>
				</nav>
			@endif
			</div>
			<button id="scrollToTop" class="footer__back-to-top mobile-only" name="back-to-top">
				<img class="back-to-top-icon" src="{{ get_stylesheet_directory_uri()}}/assets/images/icons/up-arrow.svg" alt="back-to-top icon"/>
			</button>
		</div>
	</div>

	@if ($footer_copyright)
		<p class="footer__text container">{!! "&copy; " . $footer_copyright !!}</p>
	@endif

</footer>