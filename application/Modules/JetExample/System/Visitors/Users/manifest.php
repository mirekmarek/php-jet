<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Visitor users management',
	'description' => '',

	'pages' => [
		'admin' => [
			'visitors-users' => [
				'title'                  => 'Visitors - Users',
				'relative_path_fragment' => 'visitors-users',
				'icon'                   => 'users',
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
			'visitor_users' => [
				'page_id' => 'visitors-users',
				'index'   => 100,
			],
		],
	],

];