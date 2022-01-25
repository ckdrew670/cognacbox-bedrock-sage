<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$fields = new FieldsBuilder('event_details');

if (class_exists('GFAPI')) {
	$forms = \GFAPI::get_forms();

	$acf_alias = str_replace('.php', '', basename(__FILE__));

	$fields
			->setLocation('post_type', '==', "$acf_alias")
			->addUrl('url', [
				'label' => 'Event URL',		])
			->addText('event_location', [
				'label' => 'Event location',
			])
			->addTextArea('event_summary', [
				'label' => 'Event summary',
			])
			->addDatePicker('start_date', [
				'label' => 'Event start date',
				'display_format' => 'd/m/Y',
				'return_format' => 'Y-m-d',
				'first_day' => 1,
			])
			->addTimePicker('start_time', [
				'label' => 'Event start time',
				'display_format' => 'g:i a',
				'return_format' => 'g:i a',
			])
			->addDatePicker('end_date', [
				'label' => 'Event end date',
				'required' => 0,
				'display_format' => 'd/m/Y',
				'return_format' => 'Y-m-d',
				'first_day' => 1,
			])
			->addTimePicker('end_time', [
				'label' => 'Event end time',
				'display_format' => 'g:i a',
				'return_format' => 'g:i a',
			]);
}

return $fields;
