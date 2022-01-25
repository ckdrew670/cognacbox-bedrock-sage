<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$block_field = new FieldsBuilder($block);
$block_field->setLocation('block', '==', "acf/$block")
	->addSelect('bg_color', [
		'label' => 'Background colour',
		'choices' => [
			'dark' => 'Dark Navy',
			'mid' => 'Mid Blue',
			'white' => 'White',
		],
		'default_value' => 'white'
		]);

return $block_field;