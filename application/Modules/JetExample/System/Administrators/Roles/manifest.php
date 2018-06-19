<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Administrator roles management',
	'description' => '',

	'pages' => [
		'admin' => [
			'administrators-roles' => [
				'title'                  => 'Administrators - User roles',
				'icon'                   => 'user-circle',
				'relative_path_fragment' => 'administrators-user-roles',
				'contents' => [
					[
						'controller_action' => 'default',
					]
				],
			],
		],
	],

	'menu_items' => [
		'system' => [
			'administrator_roles' => [
				//'label'   => 'Administrators - User roles',
				'page_id' => 'administrators-roles',
				//'icon'    => 'user-circle',
				'index'   => 201,
			],
		],
	],

];