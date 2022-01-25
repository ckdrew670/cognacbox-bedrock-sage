{{--
  Title: Form
  Description: Block which renders a Gravity Form
  Category: common
  Icon: feedback
  Keywords: form input field gravity
  Mode: auto
  Align: left
  PostTypes: page post
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
	$fields = Form::getFields();
	$title = $fields['title'];
	$text = $fields['body_text'];
@endphp

<section class="form container">
	<div class="form__content">
		@if($title)
			<h2 class="form__title">{{ $title }}</h2>
		@endif
		@if($text)
			{!! $text !!}
		@endif
	</div>
	<div class="form__form">
		{!! do_shortcode('[gravityform id="'.$fields['form_id'].'" title="false" description="false"  /]') !!}
	</div>
</section>