<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	{{ wp_head() }}

	{{-- This style tag means that applying a "v-cloak" attribute to
		-- a vue template will keep the curly brace templating syntax {{}}
		-- from displaying on the page before vue has had a chance to do
		-- something with it. The "v-cloak" attribute gets removed by vue
		-- as soon as it has interacted with the element, meaning that the
		-- element can once again display on the page.
	  --}}
	<style>
		[v-cloak] {
			display: none;
		}
	</style>
	@include('partials.localised-vars')
</head>
