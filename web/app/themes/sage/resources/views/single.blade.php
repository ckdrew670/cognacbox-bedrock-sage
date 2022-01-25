@extends('layouts.app')

@section('content')
	@while(have_posts()) @php the_post() @endphp
		@include('partials.single-page-content.content-single-'.get_post_type())
	@endwhile
@endsection

@section('pre-footer')
	@include('patterns.categories.categories')
	{{-- To Do related posts --}}
@endsection
