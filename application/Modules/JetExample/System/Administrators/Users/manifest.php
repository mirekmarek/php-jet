<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Administrator users management',
	'description' => '',

	'pages' => [
		'admin' => [
			'administrators-users' => [
				'title'                  => 'Administrators - Users',
				'icon'                   => 'user-secret',
				'relative_path_fragment' => 'administrators-users',
				'contents' => [
					[
						'controller_action' => 'default'
					]
				],
			],
		],
	],

	'menu_items' => [
		'system' => [
			'administrator_users' => [
				'separator_before' => true,
				'page_id'          => 'administrators-users',
				'index'            => 200,
			],
		],
	],

];