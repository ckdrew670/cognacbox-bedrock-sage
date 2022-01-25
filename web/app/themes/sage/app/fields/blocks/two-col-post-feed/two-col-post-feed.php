<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addSelect('alignment', [
				'choices' => [
					'left' => 'Post feed left',
					'right' => 'Post feed right'
				],
				'default_value' => 'right'
			])
			->addFields(get_field_partial('partials.background-color'))
			->addText('title')
			->addWysiwyg('text', [
				'label' => 'Body text'
			])
			->addLink('link')
			->addRelationship('posts', [
				'label' => 'Post Feed',
				'instructions' => 'Select up to three posts',
				'required' => 1,
				'min' => '2',
				'max' => '3',
				'return_format' => 'object',
			])
		;

return $fields;