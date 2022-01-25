<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addSelect('alignment', [
				'choices' => [
					'left' => 'Image left',
					'right' => 'Image right'
				],
				'default_value' => 'right'
			])
			->addFields(get_field_partial('partials.background-color'))
			->addText('title')
			->addWysiwyg('text', [
				'label' => 'Body text'
			])
			->addLink('link')
			->addImage('image', [
				'return' => 'id'
			])
		;

return $fields;