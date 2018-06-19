<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'REST clients users management',
	'description' => '',

	'pages' => [
		'admin' => [
			'rest-clients-users' => [
				'title'                  => 'REST clients - Users',
				'icon'                   => 'server',
				'relative_path_fragment' => 'rest-clients-users',
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
			'rest_clients_users' => [
				'separator_before' => true,
				'page_id'          => 'rest-clients-users',
				'index'            => 300,
			],
		],
	],

];