<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
	->setLocation('block', '==', "acf/$block")
	->addText('title', [
		'default_value' => 'What are you looking for?'
	])
	->addTextarea('text')
	->addText('placeholder', [
		'default_value' => 'Search'
	]);

return $fields;