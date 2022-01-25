@php
		// event details
		$event_start_date = get_field('start_date');
		$event_end_date = get_field('end_date');
		$event_start_time = get_field('start_time');
		$event_end_time = get_field('end_time');
		$event_summary = get_field('event_summary');
		$event_location = get_field('event_location');
		$event_link = get_field('url');

		// format event date
		$start_date = new DateTime(strtok($event_start_date, " "));
		$formattedDate = $start_date->format('jS F, Y');

		if ($event_start_time){
			$formattedDate .= ' '.$event_start_time;
		}

		if ($event_end_date){
			$end_date = new DateTime(strtok($event_end_date, " "));
			$formattedEndDate = $end_date->format('jS F, Y');
			$formattedDate .= ' - '.$formattedEndDate;
			if ($event_end_time){
				$formattedDate .= ' '.$event_end_time;
			}
		}elseif ($event_end_time){
			$formattedDate .= ' - '.$event_end_time;
		}

		// post details
		$post = get_post();
		$post_title = get_the_title();
		$post_type = $post->post_type;
		$post_link = get_the_permalink();
		$post_date = new DateTime(strtok($post->post_date, " "));
		$post_author = get_the_author_meta('display_name', $post->post_author);
		$excerpt = $event_summary;
		$post_tags = get_tags();
		$post_categories = get_categories();

		// featured image
		$image = get_post_thumbnail_id( $post->ID );
		$featured_image = \App\responsive_image([
			'image_id' => $image,
			'lazy_load' => false,
			'class' => 'hero__image'
		]);

		// hero
		$hero_type = get_field('type') ? get_field('type') : 'page';
		$hero_title = $post_title;
		$hero_subtitle = $formattedDate && $event_location ? $formattedDate . " | " . $event_location : '';
		$hero_copy = get_field('text') ? get_field('text') : $excerpt;

		$hero_text_color = get_field('text_color');
		$hero_bg = get_field('background') ? get_field('background') : 'color';
		$hero_background_filter = get_field('image_filter');
		if(isset(get_field('bgImage')['id'])) :
			$hero_bgImageDesktop = \App\responsive_image([
				'image_id' => get_field('bgImage')['id'],
				'lazy_load' => false,
				'class' => 'hero--homepage__bgimage--desktop'
			]);
		else :
			$hero_bgImageDesktop = $featured_image;
		endif;
		if(isset(get_field('bgImageMob')['id'])) :
			$hero_bgImageMob = \App\responsive_image([
				'image_id' => get_field('bgImageMob')['id'],
				'lazy_load' => false,
				'class' => 'hero--homepage__bgimage--mob'
			]);
		else :
			$hero_bgImageMob = $featured_image;
		endif;
		$hero_bg_color = get_field('bg_color') ? get_field('bg_color') : 'dark';
		$hero_has_button = get_field('hasButton');
		$hero_cta = get_field('cta') ? get_field('cta') : [];
@endphp

@section('content')
<main id="app">
	@include('patterns.hero.hero', [
		'type' => $hero_type,
		'title' => $hero_title,
		'subtitle' => isset($hero_subtitle) ? $hero_subtitle : '',
		'copy' => $hero_copy,
		'text_color' => $hero_text_color,
		'background_filter' => $hero_background_filter ? $hero_background_filter : '',
		'bgImageDesktop' => isset($hero_bgImageDesktop) ? $hero_bgImageDesktop : [],
		'bgImageMob' => isset($hero_bgImageMob) ? $hero_bgImageMob : [],
		'bg_color' => $hero_bg_color ? $hero_bg_color : '',
		'hasButton' => $hero_has_button,
		'cta' => $hero_cta ? $hero_cta : [],
		'bg_type' => $hero_bg
	])
	@include('patterns.breadcrumbs.breadcrumbs')
	@php the_content() @endphp
</main>
@endsection