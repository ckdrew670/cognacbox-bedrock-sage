{{--
  Title: Contact Details
  Description: Contact Details with map image
  Category: common
  Icon: admin-comments
  Keywords: contact, details, map
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$hours = get_field('hours') ? get_field('hours') : '';
	$email = get_field('email') ? get_field('email') : '';
	$telephone = get_field('telephone') ? get_field('telephone') : '';
	$address = get_field('address') ? get_field('address') : '';
	$map = \App\responsive_image([
		'image_id' => get_field('map')['id'],
		'lazy_load' => false,
	]);
	$details = array(
		'Our hours:' => $hours,
		'Email:' => $email,
		'Telephone:' => $telephone,
		'Address:' => $address
	);
	$link = get_field('map_link') ? get_field('map_link') : '';
@endphp

<section class="two-col-full-width contact-details">
	<div class="two-col-full-width__container two-col-full-width__container--right background--dark">
		<div class="two-col-full-width__text-content container">
			<ul class="contact-details__list">
				@foreach ($details as $title => $value)
						<li class="contact-details__list-item">
							<h4 class="contact-details__title text--white">{{ $title }}</h4>
							<p class="contact-details__detail text--white">{{ $value }}</p>
						</li>
				@endforeach
			</ul>
		</div>
		@if($link)
			<a href="{{ $link }}" class="two-col-full-width__image two-col-full-width__image--link">
		@else
			<div class="two-col-full-width__image">
		@endif
				@isset($map)
				@include('patterns.image.image', $map)
				@endisset
		@if($link)
			</a>
		@else
			</div>
		@endif
	</div>
</section>