@extends('layouts.pattern')

@section('content')
<div class="container">
	<aside>
		<nav class='filter-nav pattern-library__nav'>
			<details>
				<summary>
					Component List
				</summary>
				<ul>
					@foreach ($anchors as $anchor)
					<li>
						<a href='/pattern-library#{{ $anchor['url'] }}'>
							{{ $anchor['title'] }}
						</a>
					</li>
					@endforeach
				</ul>
			</details>
		</nav>
	</aside>
	@foreach ($templates as $template)
	{{--
		-- the templates are pre-rendered html
		-- so we just need to output them.
		--}}
	{!! $template !!}
	@endforeach
</div>
@endsection
