{{--
  Title: Icons grid
  Description: A grid of icons with title and links
  Category: common
  Icon: grid-view
  Keywords: grid, icons, icon, image grid
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$title = get_field('title');
		$icons = get_field('icons');

		// if($icons) {
		// 	$num_icons = count($icons);
		// 	$grid_rows = array_chunk($icons, 5);
		// }
@endphp

<section class="icons-grid panel">
	<div class="icons-grid__container container">
		<h2 class="icons-grid__title text--dark">{{ $title }}</h2>
		<div class="icons-grid__grid">
			@foreach($icons AS $icon)
			<a class="icons-grid__grid-item" href={{ $icon['url'] }}>
				<img src="{{$icon['image']['url']}}" alt="{{$icon['image']['alt']}}"/>
			</a>
			@endforeach
		</div>
	</div>
</section>


