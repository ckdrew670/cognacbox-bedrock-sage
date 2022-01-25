{{--
	Template Name: Archive Page Template
--}}

@php
	$p = App::getLinkedCptPage();
	$type = get_field('associated_cpt', $p);
	$content = get_the_content(null, null, $p);
@endphp
@extends('layouts.archive')
@section('archive-content')
	{!! apply_filters('the_content', $content) !!}
@endsection
@section('content')
	@include('patterns.posts-archive.posts-archive', [
		'type' => $type
	])
@endsection
