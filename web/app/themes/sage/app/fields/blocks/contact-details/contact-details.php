<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace( '.php', '', basename( __FILE__ ) );


$fields = new FieldsBuilder( $block );

$fields
	->setLocation( 'block', '==', "acf/$block" )
	->addText( 'hours' )
	->addText( 'email' )
	->addText( 'telephone' )
	->addText( 'address' )
	->addImage( 'map' )
	->addUrl(
		'map_link',
		array(
			'label'        => 'Maps Link',
			'instructions' => 'Add optional link to map image. To create a link from Google Maps, place a pin on the destination in Google Maps, click on "Share" and paste in the shareable link here.',
		)
	);


	return $fields;
