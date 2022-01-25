{{--
  Title: Site Search
  Description: Site wide search block
  Category: common
  Icon: search
  Keywords: search
  Mode: auto
  Align: center
--}}

@php
	$title = get_field('title');
	$text = get_field('text');
	$placeholder = get_field('placeholder');
@endphp

<section class="site-search">
	<div class="site-search__content">
		@if($title)
			<h2 class="site-search__title">{{ $title }}</h2>
		@endif
		@if($text)
			<p class="site-search__text">{{ $text }}</p>
		@endif

		<form class="site-search__form" method="GET" action="/">
			<label class="visually-hidden" for="search">Search this website</label>
			<input class="site-search__input" id="search" type="text" name="s" placeholder="{{ $placeholder }}" required aria-required="true">
			<button class="site-search__search"><p class="visually-hidden">Search</p>@include("logos.search")</button>
		</form>
	</div>

</section>


