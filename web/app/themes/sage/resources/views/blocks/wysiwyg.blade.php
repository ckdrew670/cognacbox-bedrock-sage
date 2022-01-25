{{--
  Title: Content
  Description: Content block wysiwyg
  Category: common
  Icon: welcome-write-blog
  Keywords: wysiwyg, content, text
  Mode: auto
  Align: left
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
		$content = get_field('content');
@endphp

<section class="wysiwyg">
	<div class="wysiwyg__content">{!! $content !!}</div>
</section>


