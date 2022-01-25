@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="search-page__title">Search results</h1>
	</div>
	@include('patterns.posts-archive.posts-archive', [
		'type' => 'any'
	])
@endsection
