<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$fields = new FieldsBuilder('Research Paper Details');

if (class_exists('GFAPI')) {

	$forms = \GFAPI::get_forms();

	$acf_alias = str_replace('.php', '', basename(__FILE__));

	$fields
			->setLocation('post_type', '==', "$acf_alias")
			->addRepeater('paper_authors', [
				'required' => 1,
				'label' => 'Authors',
				'min' => 1,
				'button_label' => 'Add Author',
				'layout' => 'block',
			])
				->addText('author')->endRepeater()
			->addDatePicker('paper_date', [
				'label' => 'Publication date',
				'display_format' => 'd/m/Y',
				'return_format' => 'Y-m-d',
				'first_day' => 1,
			]);
}

return $fields;
