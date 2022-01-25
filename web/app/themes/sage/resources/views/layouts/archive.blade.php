@php
	$headerOptions = App\get_nav_menu_items_by_location('primary_navigation');

	$footerOptions = App::getFooterOptions();
@endphp

<!doctype html>
<html {!! get_language_attributes() !!}>
	@include('partials.head')
	<body {{ body_class() }}>

		{{ do_action('get_header') }}

		@include('patterns.header.header', $headerOptions)
		@yield('archive-content')
		@yield('content')
		{{ do_action('get_footer') }}

		@include('patterns.footer.footer', $footerOptions)
		{{ wp_footer() }}
	</body>
</html>
