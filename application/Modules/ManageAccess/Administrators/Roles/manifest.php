<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Administrator roles management',
	'description' => '',

	'ACL_actions' => [
		'get_role'    => 'Get role data',
		'add_role'    => 'Add new role',
		'update_role' => 'Update role',
		'delete_role' => 'Delete role',
	],


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
		'admin' => [
			'system' => [
				'administrator_roles' => [
					//'label'   => 'Administrators - User roles',
					'page_id' => 'administrators-roles',
					//'icon'    => 'user-circle',
					'index'   => 201,
				],
			],
		],
	],

];