{{--
	This element expects:
		$type {str}
		$bg_color {str}
		$text {str}
		$url - this is either the link url or the file url
	And optionally:
		$attributes
		$class - colour of button
		$file - file object for a download button
		$addFileInfo - boolean value whether to show file info under button
--}}

@php
		$class = isset($bg_color) ? $bg_color : 'dark';
		$url = $type === 'link' ? (is_array($url) ? $url['url'] : $url) : $url;
		$download = $type === 'download_button' ? 'download' : '';
		if($download && isset($addFileInfo)) {
			$filenameSplit = explode('.', $file['filename']);
			$file_type = strtoupper($filenameSplit[count($filenameSplit) - 1]);
			$file_size = \App\formatBytes($file['filesize'], 0);
		}
@endphp
<div class="cta-button__container">
<a
	class="cta-button cta-button--{{ $class }}"
	@isset($url)
		href="{{$url}}"
	@endisset
	{{ $download }}
>
	<span class="cta-button__text">{{ $text }}</span>
</a>
@if(isset($addFileInfo) && $download)
<div class="cta-button__file-info">
	<p>{{ $file_type }}</p>
	<p>{{ $file_size }}</p>
</div>
@endif
</div>
