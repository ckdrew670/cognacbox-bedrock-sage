<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
	->addText('title', [
		'label' => 'Form Module Title'
	])
	->addWysiwyg('body_text', [
		'label' => 'Form Module Text',
	])
	->addFields(get_field_partial('partials.gravity_form', [
		'alias' => 'form_id',
		'label' => 'Gravity Form',
		'required' => 1,
	]));

return $fields;
