{{--
	This element expects:
	$items: {array}{assoc_array}
		$url: string
		$title: string
		$children?: {array}{assoc_array}
			$url: string
			$title: string
--}}

<header class='header' id='header' v-on:keydown.esc="closeSubmenus">
	<div class="header__container container" :class='headerClass'>
		<a class="header__logo-link" href='/' aria-label='homepage'>
			@include('logos.bevan-logo', ["className" => "header__logo"])
		</a>

		<button
			aria-controls='primary-nav'
			v-on:click='toggle'
			:aria-label='toggleAriaLabel'
			:aria-expanded='menuVisible.toString()'
			:class='toggleClass'
		>
			<span class='visually-hidden'>@{{ toggleAriaLabel }}</span>
			<div class='header__toggle__bars'></div>
			<div class='header__toggle__bars'></div>
			<div class='header__toggle__bars'></div>
		</button>

		<div :class="contentsClass">
			<div class="header__settings lg-up" v-show="showSettings" :style="{ opacity: settingsOpacity }">
				@include('patterns.language-select.language-select', [
					'header' => 'up'
				])
				@include('patterns.search-newsletter.search-newsletter', [
					'header' => 'up'
				])
			</div>


			@if (isset($items) && !empty($items))
				<nav
					aria-label='main menu'
					id='primary-nav'
					class="header__nav"
					role="navigation"
				>
					<div class="header__nav__container container">
						<ul class='header__menu'>

							@foreach ($items as $index => $item)

							<li class='header__item' :class="getLiClass({{ $index }})">

								@if (empty($item['children']))
								<a
									class='header__link'
									href='{{ $item['url'] }}'
									>
									{{ $item['title'] }}
								</a>


								@else
									<button
										class="header__subnav-toggle"
										v-on:click='toggleSubmenu({{ $index }})'
										:aria-label='getSubmenuAriaLabel({{ $index }}, "{{ $item['title'] }}")'
										:aria-expanded='({{ $index }} === activeSubmenu).toString()'>
										{{$item['title']}}
										<div
											:class='getSubmenuIconClass({{ $index }})'
										>
											@include('logos.chevron')
										</div>
									</button>
									<div
										class="header__submenu-wrapper"
										v-show="activeSubmenu === {{ $index }}"
									>
										<ul class="header__submenu">
											@foreach ($item['children'] as $child)
												<li class='header__subitem'>
													<a
														class='header__sublink'
														href='{{ $child['url'] }}'
													>
														{{ $child['title'] }}
													</a>
												</li>
											@endforeach
										</ul>
									</div>
								@endif
							</li>
							@endforeach

						</ul>
					</div>
				</nav>
			@endif
			<div class="header__settings lg-down" v-show="showSettings" :style="{ opacity: settingsOpacity }">
				@include('patterns.language-select.language-select', [
					'header' => 'down'
				])
				@include('patterns.search-newsletter.search-newsletter', [
					'header' => 'down'
				])
			</div>
		</div>
	</div>
</header>
