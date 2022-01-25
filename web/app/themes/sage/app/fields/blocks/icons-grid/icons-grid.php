<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace( '.php', '', basename( __FILE__ ) );

$fields = new FieldsBuilder( $block );

$fields
		->setLocation( 'block', '==', "acf/$block" )
			->addText( 'title' )
			->addRepeater(
				'icons',
				array(
					'min'          => 3,
					'button_label' => 'Add Icon',
					'layout'       => 'block',
				)
			)
				->addImage( 'image' )
				->addUrl( 'url' );
return $fields;
