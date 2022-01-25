<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields =  (new FieldsBuilder($block));

$fields
	->setLocation('post_type', '==', "all")
		->addText('hashtags', [
			'label' => 'Hashtags',
			'instructions' => 'Add comma separated hashtag phrases (no spaces between words) you would like to accompany this post E.g. "workshops,medicalTraining,certification.',
		]);

return $fields;