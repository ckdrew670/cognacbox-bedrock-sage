@extends('layouts.app')

@section('content')
	@if (!have_posts())
		<section class="fourohfour">
			@include('patterns.hero.hero', [
				'type' => 'page',
				'title' => 'Sorry, the page you are looking for does not exist.',
				'text_color' => 'dark',
				'copy' => '',
				'subtitle' => '',
				'background_filter' => '',
				'bgImageDesktop' => [],
				'bgImageMob' => [],
				'bg_color' => 'white',
				'hasButton' => true,
				'cta' => [
					'type' => 'link',
					'bg_color' => 'mid',
					'text' => 'Go to homepage',
					'url' => "/",
					'class' => 'mid'
				],
				'bg_type' => 'color'
			])
		</section>
	@endif
@endsection
