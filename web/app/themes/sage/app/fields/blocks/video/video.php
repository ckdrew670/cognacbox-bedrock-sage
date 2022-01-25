<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
	->addText('title')
	->addText('video_embed_URL', [
		'label' => 'Video Embed URL',
		'instructions' => 'Add the embed url for the video. For Youtube, this is usually in the form: https://www.youtube.com/embed/hQyTeXmFVzg. For Vimeo this is usually in the form: https://player.vimeo.com/video/455703494.'
	])
	->addWysiwyg('copy', [
		'label' => 'Body text'
	])
	->setLocation('block', '==', "acf/$block")
;

return $fields;