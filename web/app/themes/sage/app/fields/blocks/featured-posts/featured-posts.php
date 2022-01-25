<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addRadio('variant', [
				'choices' => [
					'full' => 'Background Colour',
					'split' => 'Split Colour Background'
				],
				'default_value' => 'split'
			])
			->addSelect('bg_color', [
				'label' => 'Background colour',
				'choices' => [
					'dark' => 'Dark Navy',
					'mid' => 'Mid Blue',
					'white' => 'White',
				],
				'default_value' => 'white'
				])->conditional('variant', '==', 'full')
			->addText('title')
			->addWysiwyg('text', [
				'label' => 'Body text'
			])
			->addTrueFalse('hasButton', [
				'label' => 'Add Button',
				'default_value' => 0
			])
			->addLink('link')->conditional('hasButton', '==', 1)
			->addRadio('post_select', [
				'label' => 'Select Posts',
				'choices' => [
					'post_type' => 'Auto-select latest up to twelve posts from a given post type',
					'manual' => 'Manually select up to twelve posts from anywhere'
				],
				'default_value' => 'manual'
			])
			->addSelect('post_type', [
				'label' => 'Select Post Type',
				'choices' => [
					'blogs' => 'Blog',
					'case-studies' => 'Case Studies',
					'events' => 'Events',
					'news' => 'News',
					'projects' => 'Projects',
					'programmes' => 'Programmes',
					'publications' => 'Publications',
					'research-papers' => 'Research Papers',
					'team' => 'Team'
				]
			])
			->addRange('num_of_posts', [
				'label' => 'Number of posts',
				'instructions' => 'Select up to twelve posts',
				'min' => '1',
				'max' => '12',
				'default_value' => '3',
			])
			->conditional('post_select', '==', 'post_type')
			->addRelationship('posts', [
				'label' => 'Post Feed',
				'instructions' => 'Select up to twelve posts',
				'required' => 1,
				'min' => '1',
				'max' => '12',
				'return_format' => 'object',
			])->conditional('post_select', '==', 'manual')
		;

return $fields;