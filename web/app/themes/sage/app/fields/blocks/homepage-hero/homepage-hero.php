<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addText('title', [
				'label' => 'Hero title'
			])
			->addWysiwyg('text', [
				'label' => 'Hero body text'
			])
			->addImage('bgImage', [
				'label' => 'Background Image - Desktop',
				'return' => 'id'
			])
			->addImage('bgImageMob', [
				'label' => 'Background Image - Mobile',
				'return' => 'id'
			])
			->addFields(get_field_partial('partials.text-color'))
			->addFields(get_field_partial('partials.image-filter'))
		;

return $fields;