<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
		->addTextarea('text', [
			'label' => 'Banner body text',
			'maxlength' => '250',
      'rows' => '2',
		])
		->addLink('link')
;

return $fields;