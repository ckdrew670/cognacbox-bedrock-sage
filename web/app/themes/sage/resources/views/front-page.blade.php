@extends('layouts.app')

@section('content')
<main id="app">
	@while(have_posts()) @php the_post() @endphp
		@php the_content() @endphp
	@endwhile
</main>
@endsection