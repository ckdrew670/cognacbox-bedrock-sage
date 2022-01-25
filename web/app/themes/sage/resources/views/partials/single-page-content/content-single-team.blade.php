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

		// team member details

		$name = get_field('name');
		$position = get_field('position');
		$email = get_field('email');
		$phone = get_field('phone');
		$about = get_field('about');
		$image = \App\responsive_image([
			'image_id' => get_field('image')['id'],
			'lazy_load' => false,
			'class' => 'roundel team-page__about__image'
		]);
		$social_links = get_field('social_links');

		$has_posts = get_field('has_posts');

		$post_block_title = get_field('posts_title');
		$post_block_copy = get_field('posts_copy');

		// GET POSTS
		$featured_post_author = get_field('featured_author');
		$featured_post_type = get_field('featured_cpt');
		$args = array(
			'numberposts' => 3,
			'order'=> 'DESC',
			'orderby' => 'date',
			'post_type' => $featured_post_type,
			'author' => $featured_post_author
		);
		$posts = get_posts($args);

		// hero
		$hero_type = 'team';
		$hero_title = $name;
		$hero_subtitle = $position ? $position : '';
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
@endphp

@section('content')
<main id="app">
	@if($hero_title)
	@include('patterns.hero.hero', [
		'type' => $hero_type,
		'title' => $hero_title,
		'subtitle' => $hero_subtitle ? $hero_subtitle : '',
		'text_color' => $hero_text_color,
		'background_filter' => $hero_background_filter ? $hero_background_filter : '',
		'bgImageDesktop' => $hero_bgImageDesktop ? $hero_bgImageDesktop : [],
		'bgImageMob' => $hero_bgImageMob ? $hero_bgImageMob : [],
		'bg_color' => $hero_bg_color ? $hero_bg_color : '',
		'hasButton' => false,
		'bg_type' => $hero_bg,
	])
	@endif

	@include('patterns.breadcrumbs.breadcrumbs')
	@php the_content() @endphp

	<section class="team-page__about panel">
		<div class="team-page__about__container container">
			<article class="team-page__about__details">
				{{-- IMAGE --}}
				@if($image)
					@include('patterns.image.image', $image)
				@endif
				<div class="team-page__about__text-content">
					{{-- CONTACT DETAILS --}}
					<div class="team-page__about__info">
						<div class="team-page__about__details">
								@if($email)
								<h3 class="team-page__about__heading text--dark">Email:</h3>
								<a href="mailto:{{ $email }}" class="team-page__about__email">{{ $email }}</a>
								@endif
								@if($phone)
									<h3 class="team-page__about__heading text--dark">Telephone:</h3>
									<p class="team-page__about__phone text--dark">{{ $phone }}</p>
								@endif
						</div>
						{{-- SOCIALS --}}
						<ul class="team-page__about__socials">
							@if ($social_links)
								@foreach ($social_links as $item)
									<li class="team-page__about__socials__list-item">
										<a href='{{ $item['url'] }}'><img class="{{strtolower($item['social_media_platform'])}}-icon" src="{{ get_stylesheet_directory_uri()}}/assets/images/icons/{{strtolower($item['social_media_platform'])}}-blue.svg" alt="{{strtolower($item['social_media_platform'])}} icon"/>Follow on {{ ucfirst($item['social_media_platform']) }}</a>
									</li>
								@endforeach
							@endif
						</ul>
					</div>
				</div>
			</article>
			<article class="team-page__about--right">
				<div class="team-page__about__text">{!! $about !!}</div>
			</article>
		</div>
	</section>
	@if($has_posts)
		@include('patterns.featured.featured', [
			'variant' => 'split',
			'bg' => 'background--mid',
			'title' => $post_block_title,
			'text' => $post_block_copy,
			'posts' => $posts,
			'hasButton' => 1,
			'button_color' => 'dark',
			'link' => '/author/'. $featured_post_author,
			'link_title' => 'View more by ' . $name
		])
	@endif
</main>
@endsection