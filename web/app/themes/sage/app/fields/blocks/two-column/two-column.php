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
			->addText('title')
			->addWysiwyg('text', [
				'label' => 'Body text',
			])
			->addImage('image', [
				'return' => 'id'
			])
			->addTrueFalse('hasButton', [
				'label' => 'Add CTA Button',
				'default_value' => 0
			])
			->addFields(get_field_partial('partials.cta-button'))
			->getField('cta')
				->conditional('type', '==', 'page')->and('hasButton', '==', 1)
		;

return $fields;