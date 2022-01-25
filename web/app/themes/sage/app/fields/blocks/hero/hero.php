<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addRadio('type', [
				'label' => 'Variants',
				'choices' => [
					'page' => 'Page Hero',
					'team' => 'Team Member Page Hero'
				],
				'default_value' => 'page'
			])
			->addText('title', [
				'label' => 'Custom Hero title'
			])
			->addText('subtitle', [
				'label' => 'Custom Hero subtitle'
			])
			->addWysiwyg('text', [
				'label' => 'Hero body text'
			])->conditional('type', '==', 'page')
			->addRadio('background', [
				'choices' => [
					'image' => 'Background Image',
					'color' => 'Background Colour'
				],
				'default_value' => 'color'
			])
			->addImage('bgImage', [
				'label' => 'Background Image - Desktop',
				'return' => 'id'
			])->conditional('background', '==', 'image')
			->addImage('bgImageMob', [
				'label' => 'Background Image - Mobile',
				'return' => 'id'
			])->conditional('background', '==', 'image')
			->addRange('image_filter', [
				'label' => 'Image Brightness',
				'instructions' => 'Adjust the brightness of the background image',
				'default_value'	=> '100',
				'min'			=> '0',
				'max'			=> '100',
				'step'			=> '1',
				'prepend' => '0%'
				])->conditional('background', '==', 'image')
			->addSelect('text_color', [
				'label' => 'Text colour',
				'choices' => [
					'dark' => 'Dark',
					'white' => 'White',
				],
				'default_value' => 'white'
				])->conditional('background', '==', 'image')
			->addSelect('bg_color', [
				'label' => 'Background colour',
				'choices' => [
					'dark' => 'Dark Navy',
					'mid' => 'Mid Blue',
					'white' => 'White',
				],
				'default_value' => 'dark'
			])->conditional('background', '==', 'color')
			->addTrueFalse('hasButton', [
				'label' => 'Add CTA Button',
				'default_value' => 0
			])->conditional('type', '==', 'page')
			->addFields(get_field_partial('partials.cta-button'))
			->getField('cta')
				->conditional('type', '==', 'page')->and('hasButton', '==', 1)
		;

return $fields;