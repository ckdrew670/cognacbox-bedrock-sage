@php
	$headerOptions = json_decode(
		file_get_contents(
			get_stylesheet_directory()
			. '/views/patterns/header/header.json'
		),
		true
	)['data'];

	$footerOptions = json_decode(
		file_get_contents(
			get_stylesheet_directory()
			. '/views/patterns/footer/footer.json'
		),
		true
	)['data'];

@endphp

<!doctype html>
<html {!! get_language_attributes() !!}>
	@include('partials.head')
	<body {{ body_class() }}>

		{{ do_action('get_header') }}

		@include('patterns.header.header', $headerOptions)

		@yield('content')

		{{ do_action('get_footer') }}
		@include('patterns.footer.footer', $footerOptions)
		{{ wp_footer() }}
	</body>
</html>
