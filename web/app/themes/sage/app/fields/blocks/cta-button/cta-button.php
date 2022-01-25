<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));


$fields = new FieldsBuilder($block);

$fields
	->setLocation('block', '==', "acf/$block")
	->addText('title')
	->addWysiwyg('copy')
	->addFields(get_field_partial('partials.background-color'))
	->addRadio('variant', [
		'label' => 'Button Block Variant',
		'choices' => [
			'image' => 'Buttons with image and text',
			'text' => 'Buttons with text',
			'button' => 'Buttons without image or text'
		],
		'default_value' => 'button'
	])
	->addRepeater('buttons_with_image',[
		'layout' => 'block',
		'min' => 1,
		'max' => 6
	])->conditional('variant', '==', 'image')
		->addImage('image')
		->addText('button_copy')
		->addFields(get_field_partial('partials.cta-button'))
		->endRepeater()
	->addRepeater('buttons_with_text',[
		'min' => 1,
		'max' => 6,
		'layout' => 'block'
	])->conditional('variant', '==', 'text')
		->addText('button_copy')
		->addFields(get_field_partial('partials.cta-button'))
		->endRepeater()
	->addRepeater('buttons',[
		'min' => 1,
		'max' => 6,
		'layout' => 'block',
	])->conditional('variant', '==', 'button')
		->addFields(get_field_partial('partials.cta-button'))
		->endRepeater()
	;


return $fields;
