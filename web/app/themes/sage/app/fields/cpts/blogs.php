<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$fields = new FieldsBuilder('blog_post_author');


	$fields
			->setLocation('post_type', '==', "blogs")
			->addText('author');


return $fields;