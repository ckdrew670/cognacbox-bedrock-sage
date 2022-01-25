{{--
	This element expects:
		$sources: {array} -
			'srcset' => 'http://site.local/path/to/image.jpg',
			'mq' => "(min-width: 992px)"
		$image: {string} - url for main image
		$alt: {string} - alt text
		$lazy_load: {bool} - whether to use lazy loading
		$class: {string} - class to add
	And optionally:
		$attributes: {array}{assoc_array} - HTML attributes
--}}

@if (isset($image) && !empty($image))
<picture
	@if (isset($class) && $class)
		class="{{ $class }}"
	@else
		class="image"
	@endif

	@if (isset($attributes))
		@foreach ($attributes as $key => $value)
	 		{{ $key }}="{{ $value }}"
		@endforeach
	@endif
>
@foreach ($sources as $source)
	<source
		srcset="{{$source['srcset']}}"
		media="{{$source['mq']}}"
	/>
@endforeach
	<img src="{{$image}}" alt="{{$alt}}"/>
</picture>
@endif
