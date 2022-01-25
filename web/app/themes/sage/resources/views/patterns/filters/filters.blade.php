{{--
	This element expects

	$type {str}
--}}

@php
// if type = any then get all taxonomies
	$taxonomyNames = $type !== 'any' ? collect(get_object_taxonomies($type)) : collect([]);

	$taxonomyNamesFormatted = $taxonomyNames->map(function($name) {
		return str_replace('_', '-', $name);
	});

	$taxonomies = $taxonomyNames->map(function($name) {
		$taxonomy = get_taxonomy($name);
		$terms = get_terms($name);
		return ['label' => $taxonomy->label, 'name' => str_replace('_', '-', $taxonomy->name), 'terms' => $terms];
	});
@endphp

<aside id="filters" class="filters" data-search="{{ $type === 'any' ? 's' : 'search' }}" data-taxonomies="{{ json_encode($taxonomyNamesFormatted) }}">
	@foreach($taxonomies as $index => $taxonomy)
		<div class="filter">
			<h3 class="filter__title">{{ $taxonomy['label']}}</h3>
			<img
				tabindex="0"
				role="button"
				aria-pressed="false"
				class="filter__arrow"
				v-on:click='toggleFilter({{ $index }})'
				v-on:keyup.enter='toggleFilter({{ $index }})'
				src="@asset('images/chevron-down_white.svg')"
				alt="expand chevron"
			>
			<div
				class="filter__checkboxes"
				:style="{ maxHeight: checkboxesHeight({{ $index }}, {{ count($taxonomy['terms']) }})}"
				:aria-expanded='filterIsVisible({{ $index }})'
			>
				@foreach($taxonomy['terms'] as $term)
					<div class="filter__checkbox">
						<label class="filter__label" for="{{ $term->term_id }}">
						<input
							id="{{ $term->term_id }}"
							type="checkbox"
							v-bind:tabindex="filterIsVisible({{ $index }}) ? 0 : -1"
							name="{{ $term->slug }}"
							value="{{ $term->name }}"
							data-taxonomy="{{ $taxonomy['name'] }}"
							data-term="{{ $term->term_id }}"
							v-on:change="updateTermsParam($event)"
							:checked="isChecked({{$term->term_id}})"
						/>
							{{ $term->name }}
						</label>
					</div>
				@endforeach
			</div>
		</div>
	@endforeach
	<div class="filters__search">
		<label for="search" class="visually-hidden">Search</label>
		<input
			id="search"
			class="posts__search__input"
			type="text"
			placeholder="Search"
			v-model="search"
			v-on:keyup.enter="updateParams()"
		/>
		<img
			class="filters__search__icon"
			src="@asset('images/search--grey.svg')"
			alt="search icon"
			v-on:click='updateParams()'
		/>
	</div>
</aside>