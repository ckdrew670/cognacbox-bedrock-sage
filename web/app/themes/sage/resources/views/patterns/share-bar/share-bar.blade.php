{{--
	This element expects:
	And optionally:
--}}

@php

	use App\Controllers\SocialLinks;

 	$iconBaseUrl = get_stylesheet_directory_uri() . "/assets/images/icons/";

	$hashtags = (get_field('hashtags'))? get_field('hashtags') : '';

	$shareLinks = new SocialLinks($hashtags);
@endphp

<aside class="share-bar">
		<div class="share-bar__content">
			{{-- TODO TRANSLATION --}}
			<h3 class="share-bar__text">Share:</h3>
			<ul class="share-bar__link-wrapper">
				<li class="share-bar__link-item">
					<a
						class="share-bar__link"
						href="<?php echo $shareLinks->facebookShare() ?>"
						aria-label="Share on FaceBook"
					>
						<img
							class="share-bar__img"
							src="{{$iconBaseUrl . "facebook-blue.svg"}}"
							alt="FaceBook Logo"
						>
					</a>
				</li>
				<li class="share-bar__link-item">
					<a
						class="share-bar__link"
						href="<?php echo $shareLinks->twitterShare() ?>"
						aria-label="Share on Twitter"
					>
						<img
							class="share-bar__img"
							src="{{$iconBaseUrl . "twitter-blue.svg"}}"
							alt="Twitter logo"
						>
					</a>
				</li>
				<li class="share-bar__link-item">
					<a
						class="share-bar__link"
						href="<?php echo $shareLinks->linkedinShare() ?>"
						aria-label="Share on LinkedIn"
					>
						<img
							class="share-bar__img"
							src="{{$iconBaseUrl . "linkedin-blue.svg"}}"
							alt="LinkedIn icon"
						>
					</a>
				</li>
			</ul>
		</div>
</aside>
