<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));
$fields = new FieldsBuilder($block);

if (class_exists('GFAPI')) {

	$forms = \GFAPI::get_forms();

	$choices = collect($forms)->reduce(function ($arr, $item) {
		$arr[$item['id']] = $item['title'];
		return $arr;
	}, []);

	$block = $alias . '_gravity_form';

	$fields
		->addSelect('form_id', [
			'label' => $label,
			'choices' => $choices,
			'required' => $required
		]);

}

return $fields;