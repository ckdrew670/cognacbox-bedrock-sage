<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

if (function_exists('acf_add_options_page')){
	acf_add_options_page([
		'page_title' => get_bloginfo('name') . ' Footer Options',
		'menu_title' => 'Footer Options',
		'menu_slug'  => 'footer_options',
		'capability' => 'edit_theme_options',
		'position'   => '999',
		'autoload'   => true
	]);
}

$footer_options = new FieldsBuilder('footer_options');

$footer_options->setLocation('options_page', '==', 'footer_options')
	->addImage('footer_logo', [
			'label' => 'Footer logo',
			'required' => 1,
			'return_format' => 'id',
			'preview_size' => 'thumbnail',
	])
	->addRepeater('menu_links', [
		'min' => 1,
    'max' => 6,
		'button_label' => 'Add Link',
    'layout' => 'block',
	])
		->addLink('link')
		->endRepeater()
	->addRepeater('social_links', [
		'min' => 1,
    'max' => 4,
		'button_label' => 'Add Social Link',
    'layout' => 'block',
	])
		->addSelect('social_media_platform',[
			'choices' => [
				'twitter' => 'Twitter',
				'linkedin' => 'LinkedIn',
				'facebook' => 'Facebook',
				'instagram' =>  'Instagram',
				'youtube' =>  'YouTube',
			],
			'default_value' => 'Twitter'
		])
		->addUrl('url', [
			'instructions' => 'Add the full url for the socials page, e.g. "https://twitter.com"'
		])
		->endRepeater()
	->addText("footer_copyright", [
		'label' => 'Footer Copyright Text'
	])
	;

return $footer_options;