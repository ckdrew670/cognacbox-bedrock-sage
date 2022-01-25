{{--
	This element expects:
	$header: str - down or up
	And optionally:
--}}

<div id="language__wrapper" class="language">
	<label class="language__select-label-{{ $header }}" id="language-label-{{ $header }}">Language <span aria-hidden="true">|</span></label>
	<select id="language__{{ $header }}" type="select" class="language__select" aria-labelledby="language-label-{{ $header }}">
		<option aria-label="English" value="ENG">ENG</option>
		<option aria-label="Cymru" value="CYM">CYM</option>
	</select>
</div>