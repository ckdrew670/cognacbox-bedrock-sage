<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$fields = new FieldsBuilder('team_member_details');

if (class_exists('GFAPI')) {
	$forms = \GFAPI::get_forms();

	$acf_alias = str_replace('.php', '', basename(__FILE__));
	$authors = collect(get_users(['public' => true], 'objects'))->mapWithKeys(function ($author) {
		return [$author->ID => $author->display_name];
	});
	$formatted_cpts = collect(get_post_types(['public' => true], 'objects'))->mapWithKeys(function ($cpt) {
			return [$cpt->name => $cpt->label];
	});
	$fields
			->setLocation('post_type', '==', "$acf_alias")
			->addText('name', [
				'label' => 'Full Name'
			])
			->addText('position', [
				'label' => 'Job title'
			])
			->addEmail('email')
			->addText('phone', [
				'instructions' => 'Add in the following format, including country code: +44 (0) 1234 567890. Number will be displayed as written.'
			])
			->addWysiwyg('about', [
				'label' => 'About text',
			])
			->addImage('image', [
				'label' => 'Image',
				'return' => 'id'
			])
			->addRepeater('social_links', [
				'max' => 4,
				'button_label' => 'Add Social Link',
				'layout' => 'block',
			])
			->addSelect('social_media_platform',[
				'choices' => [
					'twitter' => 'Twitter',
					'linkedin' => 'LinkedIn',
					'facebook' => 'Facebook',
					'instagram' =>  'Instagram'
				],
				'default_value' => 'Twitter'
			])
			->addUrl('url', [
				'instructions' => 'Add the full url for the socials page, e.g. "https://twitter.com"'
			])
			->endRepeater()
			->addTrueFalse('has_posts', [
				'label' => 'Add post block',
				'default_value' => 0
			])
			->addText('posts_title')->conditional('has_posts', '==', 1)
			->addTextarea('posts_copy')->conditional('has_posts', '==', 1)
			->addSelect('featured_author', [
				'label' => 'Author',
				'instructions' => 'Choose an author',
				'choices' => $authors
			])->conditional('has_posts', '==', 1)
			->addSelect('featured_cpt', [
					'label' => 'Post Type',
					'instructions' => 'Choose which post type you want to display for this author',
					'choices' => $formatted_cpts,
					'default_value' => ['any']
			])->conditional('has_posts', '==', 1)
		;
}

return $fields;
