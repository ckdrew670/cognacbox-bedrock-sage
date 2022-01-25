<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$block_field = new FieldsBuilder($block);
$block_field->setLocation('block', '==', "acf/$block")
	->addRange('image_filter', [
		'label' => 'Image Brightness',
		'instructions' => 'Adjust the brightness of the background image',
		'default_value'	=> '60',
    'min'			=> '0',
    'max'			=> '100',
    'step'			=> '1',
		'prepend' => '0%'
		]);

return $block_field;