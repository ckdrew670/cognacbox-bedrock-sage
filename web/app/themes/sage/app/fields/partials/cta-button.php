<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$block_field = new FieldsBuilder($block);
$block_field->setLocation('block', '==', "acf/$block")
	->addGroup('cta', ['label' => 'CTA Button'])
	->addText('text', [
		'label' => 'Text',
	])
	->addSelect('type', [
		'label' => 'Button type',
		'required' => 1,
		'choices' => [
			'download_button' => 'Download Button',
			'link' => 'Link',
		],
		'default_value' => 'link'
	])
	->addSelect('class', [
		'label' => 'Background colour',
		'required' => 1,
		'choices' => [
			'dark' => 'Dark Blue',
			'mid' => 'Light Blue',
		],
		'default_value' => 'dark'
	])
	->addLink('url', [
		'label' => 'Link',
		'required' => 1
	])->conditional('type', '==', 'link')
	->addFile('download', [
		'label' => 'CTA Download File',
		'required' => 1,
	])->conditional('type', '==', 'download_button')
	->endGroup();

return $block_field;