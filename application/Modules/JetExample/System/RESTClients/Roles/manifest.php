<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'REST clients roles management',
	'description' => '',

	'pages' => [
		'admin' => [
			'rest-clients-roles' => [
				'title'                  => 'REST clients - User roles',
				'icon'                   => 'lock',
				'relative_path_fragment' => 'rest-clients-user-roles',
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
			'rest_clients_roles' => [
				'page_id' => 'rest-clients-roles',
				'index'   => 301,
			],
		]
	],

];