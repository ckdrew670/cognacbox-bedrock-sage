mkdir app/fields/blocks/$1
cp app/fields/block-template.php app/fields/blocks/$1/$1.php
touch resources/views/blocks/$1.blade.php

echo "{{--
  Title: $i
  Description:
  Category: common
  Icon: editor-ul
  Keywords: test
  Mode: auto
  Align: left
  PostTypes: page post
  SupportsAlign: left center right
  SupportsMode: false
  SupportsMultiple: true
--}}

@php
// Fields here
@endphp
@if(true)
<div></div>
@endif" > resources/views/blocks/$1.blade.php
