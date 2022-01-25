<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$acf_alias = str_replace( '.php', '', basename( __FILE__ ) . '_posts_hero_content' );

$fields = new FieldsBuilder( 'hero_content' );

$fields
		->setLocation( 'post_type', '==', 'all' )->and( 'post_type', '!=', 'page' )
		->addFields( get_field_partial( 'partials.hero' ) );
$fields;

return $fields;
