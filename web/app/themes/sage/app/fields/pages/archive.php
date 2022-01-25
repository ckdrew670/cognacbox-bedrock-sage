<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$formatted_cpts = collect(get_post_types(['public' => true], 'objects'))->mapWithKeys(function ($cpt) {
	return [$cpt->name => $cpt->label];
});
$fields = new FieldsBuilder('page');

$fields
		->setLocation('post_type', '==', 'page')->and('page_template', '==', 'views/archive.blade.php');

$fields
	->addSelect('associated_cpt', [
		'label' => 'Associated Post Type',
		'instructions' => 'Used to link page content to an archive page',
		'choices' => $formatted_cpts,
		'default_value' => ['all'],
	]);

return $fields;