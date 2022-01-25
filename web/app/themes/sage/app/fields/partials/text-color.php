<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$block_field = new FieldsBuilder($block);
$block_field->setLocation('block', '==', "acf/$block")
	->addSelect('text_color', [
		'label' => 'Text colour',
		'choices' => [
			'dark' => 'Dark',
			'white' => 'White',
		],
		'default_value' => 'dark'
		]);

return $block_field;