<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
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
					'post_type' => 'Auto-select latest four posts from a given post type',
					'manual' => 'Manually select four posts from anywhere'
				],
				'default_value' => 'manual'
			])
			->addSelect('post_type', [
				'label' => 'Select Post Type',
				'choices' => [
					'post' => 'Blog',
					'case-studies' => 'Case Studies',
					'events' => 'Events',
					'news' => 'News',
					'projects' => 'Projects',
					'programmes' => 'Programmes',
					'publications' => 'Publications',
					'research-papers' => 'Research Papers',
					'team' => 'Team'
				]
			])->conditional('post_select', '==', 'post_type')
			->addRelationship('posts', [
				'label' => 'Post Feed',
				'instructions' => 'Select four posts',
				'required' => 1,
				'min' => '4',
				'max' => '4',
				'return_format' => 'object',
			])->conditional('post_select', '==', 'manual')
		;

return $fields;