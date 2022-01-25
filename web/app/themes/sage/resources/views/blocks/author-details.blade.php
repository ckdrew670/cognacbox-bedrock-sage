{{--
  Title: Author Details
  Description: Author details block with information about a specified person
  Category: common
  Icon: admin-users
  Keywords: author, details, person, team
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$is_team_member = get_field('add_team_member') === 'team';
		if(!$is_team_member) :
			$image = \App\responsive_image([
					'image_id' => get_field('image')['id'],
					'lazy_load' => false,
					'class' => 'roundel author-details__image'
				]);
			$position = get_field('position');
			$email = get_field('email');
			$telephone = get_field('telephone');
			$social_links = get_field('social_links');
			$name = get_field('author');
		elseif($is_team_member) :
			$post_id = get_field('team_member')->ID;
			$team_member = get_fields($post_id);
			$name = $team_member['name'];
			$position = $team_member['position'];
			$email = $team_member['email'];
			$telephone = $team_member['phone'];
			$image = \App\responsive_image([
				'image_id' => $team_member['image']['id'],
				'lazy_load' => false,
				'class' => 'roundel author-details__image'
			]);
			$social_links = $team_member['social_links'];
			$link = get_permalink($post_id);
		endif;
@endphp

<section class="author-details panel">
	<div class="author-details__container container">
		{{-- IMAGE --}}
		@if($image)
			@include('patterns.image.image', $image)
		@endif
		<div class="author-details__text-content">
			@if($name)
				<h2 class="author-details__name text--dark">{{ $name }}</h2>
			@endif
			{{-- CONTACT DETAILS --}}
			<div class="author-details__info">
				<div class="author-details__details">
						@if($position)
						<h3 class="author-details__heading text--dark">Job Title:</h3>
						<p class="author-details__position text--dark">{{ $position }}</p>
						@endif
						@if($email)
						<h3 class="author-details__heading text--dark">Email:</h3>
						<a href="mailto:{{ $email }}" class="author-details__email">{{ $email }}</a>
						@endif
						@if($telephone)
							<h3 class="author-details__heading text--dark">Telephone:</h3>
							<p class="author-details__telephone text--dark">{{ $telephone }}</p>
						@endif
				</div>
				{{-- SOCIALS --}}
				<div class="author-details__right">
					<ul class="author-details__socials">
						@if ($social_links)
							@foreach ($social_links as $item)
								<li class="author-details__socials__list-item">
									<a href='{{ $item['url'] }}'><img class="{{strtolower($item['social_media_platform'])}}-icon" src="{{ get_stylesheet_directory_uri()}}/assets/images/icons/{{strtolower($item['social_media_platform'])}}-blue.svg" alt="{{strtolower($item['social_media_platform'])}} icon"/>Follow on {{ ucfirst($item['social_media_platform']) }}</a>
								</li>
							@endforeach
						@endif
					</ul>
					{{-- BUTTON --}}
					@if($is_team_member)
						@include('patterns.cta-button.cta-button', [
							'bg_color' => 'dark',
							'text' => 'View Profile',
							'url' => $link,
							'type' =>  'link',
						])
					@endif
				</div>
			</div>
		</div>
	</div>
</section>


