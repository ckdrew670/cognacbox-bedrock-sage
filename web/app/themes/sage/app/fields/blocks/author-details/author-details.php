<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

$block = str_replace('.php', '', basename(__FILE__));

$fields = new FieldsBuilder($block);

$fields
		->setLocation('block', '==', "acf/$block")
			->addSelect('add_team_member', [
				'choices' => [
					'team' => 'Add Existing Team Member Details',
					'new' => 'Add Custom'
				],
				'default_value' => 'team'
			])
			->addPostObject('team_member', [
					'label' => 'Team Member',
					'required' => 1,
					'post_type' => 'team',
					'return_format' => 'object',
					'ui' => 1,
    		])->conditional('add_team_member', '==', 'team')
			->addImage('image', [
				'label' => 'Portrait Image',
				'return' => 'id'
			])->conditional('add_team_member', '==', 'new')
			->addText('author', [
				'label' => 'Name'
			])->conditional('add_team_member', '==', 'new')
			->addText('position', [
				'label' => 'Job Role'
			])->conditional('add_team_member', '==', 'new')
			->addEmail('email')->conditional('add_team_member', '==', 'new')
			->addText('telephone', [
				'instructions' => 'Add in the following format, including country code: +44 (0) 1234 567890. Number will be displayed as written.'
			])->conditional('add_team_member', '==', 'new')
			->addRepeater('social_links', [
				'max' => 4,
				'button_label' => 'Add Social Link',
				'layout' => 'block',
			])->conditional('add_team_member', '==', 'new')
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
		;
return $fields;