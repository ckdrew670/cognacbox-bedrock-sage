{{--
	This element expects:
		nowt
	And optionally:
--}}

@php
		global $post;
		$terms = App::getAllTerms();
		$type = $post->post_type;
@endphp

@if (count($terms) > 0)
	<aside class="categories">
		<ul class="categories__list">
			@foreach ($terms as $term )
				@if($type === 'programmes')
				<li class="categories__list-item">
					<a href="{{ get_home_url() }}/?s={{ $term->name }}">{{ $term->name }}</a>
				</li>
				@else
				<li class="categories__list-item">
					@php $formattedTaxonomy = str_replace('_', '-', $term->taxonomy); @endphp
					<a href="{{ get_home_url() }}/{{ $type }}/?{{ $formattedTaxonomy }}={{ $term->term_id }}">{{ $term->name }}</a>
				</li>
				@endif
			@endforeach
		</ul>
	</aside>
@endif