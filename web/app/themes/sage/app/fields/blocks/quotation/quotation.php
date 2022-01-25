<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addText('author', [
				'label' => 'Name'
			])
			->addText('position', [
				'label' => 'Job Role'
			])
			->addWysiwyg('text', [
				'label' => 'Quotation text',
				'required' => 1
			])
			->addImage('image', [
				'label' => 'Portrait Image (optional)',
				'return' => 'id'
			])
			->addFields(get_field_partial('partials.background-color'))
		;
return $fields;