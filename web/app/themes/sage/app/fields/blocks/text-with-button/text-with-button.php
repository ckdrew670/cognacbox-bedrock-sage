<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addFields(get_field_partial('partials.background-color'))
			->addText('title')
			->addWysiwyg('text', [
				'label' => 'Body text'
			])
			->addSelect('alignment', [
				'label' => 'Text alignment',
				'choices' => [
						'left' => 'Left',
						'center' => 'Centre',
						'right' => 'Right'
				],
				'default_value' => 'center'
			])
			->addFields(get_field_partial('partials.cta-button'))
		;

return $fields;