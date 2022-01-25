{{--
	This element expects:

	And optionally:
	$header: str - up or down
--}}

<div class="search-newsletter">
	<a class="search-newsletter__newsletter" href="/newsletter">Newsletter</a>
<form class="search-newsletter__search-form" method="GET" action="/">
	<label class="visually-hidden" for="search_{{ $header }}">Search this website</label>
	<input class="search-newsletter__search-input" id ="search_{{ $header }}" type="search" name="s" placeholder="Type here..." required aria-required="true">
	<button class="search-newsletter__search">Search  @include("logos.search")</button>
</form>

</div>