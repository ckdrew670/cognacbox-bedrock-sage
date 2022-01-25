@php
		// post details
		$post = get_post();
		$post_title = get_the_title();
		$post_type = $post->post_type;
		$post_link = get_the_permalink();
		$post_categories = get_categories();
		$post_tags = get_tags();
		$post_date = new DateTime(strtok($post->post_date, " "));
		$formattedDate = $post_date->format('M d, Y');
		$author = get_the_author_meta('display_name', $post->post_author);
		$excerpt = get_the_excerpt($post);

		// featured image
		$featured_image = get_post_thumbnail_id( $post->ID );

		// hero
		$hero_type = get_field('type') ? get_field('type') : 'page';
		$hero_title = $post_title;
		$hero_subtitle = get_field('subtitle') ? get_field('subtitle') : '';
		$hero_copy = get_field('text');
		$hero_bg = get_field('background') ? get_field('background') : 'color';
		$hero_text_color = get_field('text_color');
		$hero_background_filter = get_field('image_filter');
		if(isset(get_field('bgImage')['id'])) :
			$hero_bgImageDesktop = \App\responsive_image([
				'image_id' => get_field('bgImage')['id'],
				'lazy_load' => false,
				'class' => 'hero--homepage__bgimage--desktop'
			]);
		else :
			$hero_bgImageDesktop = \App\responsive_image([
			'image_id' => $featured_image,
			'lazy_load' => false,
			'class' => 'hero--homepage__bgimage--desktop'
		]);
		endif;
		if(isset(get_field('bgImageMob')['id'])) :
			$hero_bgImageMob = \App\responsive_image([
				'image_id' => get_field('bgImageMob')['id'],
				'lazy_load' => false,
				'class' => 'hero--homepage__bgimage--mob'
			]);
		else :
			$hero_bgImageMob = \App\responsive_image([
			'image_id' => $featured_image,
			'lazy_load' => false,
			'class' => 'hero--homepage__bgimage--mob'
		]);
		endif;
		$hero_bg_color = get_field('bg_color') ? get_field('bg_color') : 'dark';
		$hero_has_button = get_field('hasButton');
		$hero_cta = get_field('cta') ? get_field('cta') : [];
@endphp

@section('content')
<main id="app">
	@if($hero_title)
	@include('patterns.hero.hero', [
		'type' => $hero_type,
		'title' => $hero_title,
		'subtitle' => $hero_subtitle,
		'copy' => $hero_copy,
		'text_color' => $hero_text_color,
		'background_filter' => $hero_background_filter ? $hero_background_filter : '',
		'bgImageDesktop' => $hero_bgImageDesktop ? $hero_bgImageDesktop : [],
		'bgImageMob' => $hero_bgImageMob ? $hero_bgImageMob : [],
		'bg_color' => $hero_bg_color ? $hero_bg_color : '',
		'hasButton' => $hero_has_button,
		'cta' => $hero_cta ? $hero_cta : [],
		'bg_type' => $hero_bg
	])
	@endif
	@include('patterns.breadcrumbs.breadcrumbs')
	@php the_content() @endphp
</main>
@endsection