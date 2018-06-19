<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Visitor roles management',
	'description' => '',

	'pages' => [
		'admin' => [
			'visitors-roles' => [
				'title'                  => 'Visitors - User roles',
				'relative_path_fragment' => 'visitors-user-roles',
				'icon'                   => 'street-view',
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
			'visitor_roles' => [
				'page_id' => 'visitors-roles',
				'index'   => 101,
			],
		],
	],

];