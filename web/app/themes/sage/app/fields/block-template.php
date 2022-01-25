<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

return (new FieldsBuilder($block))

	->setLocation('block', '==', "acf/$block")

	// add fields here, e.g.:
	// ->addText('example', [/* ...options... */])
;