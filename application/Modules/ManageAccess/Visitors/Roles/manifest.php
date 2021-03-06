<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek@web-jet.cz>',
	'label'       => 'Visitor roles management',
	'description' => '',


	'ACL_actions' => [
		'get_role'    => 'Get role data',
		'add_role'    => 'Add new role',
		'update_role' => 'Update role',
		'delete_role' => 'Delete role',
	],

	'pages' => [
		'admin' => [
			'visitors-roles' => [
				'title'                  => 'Visitors - User roles',
				'relative_path_fragment' => 'visitors-user-roles',
				'icon'                   => 'street-view',
				'contents'               => [
					[
						'controller_action' => 'default'
					]
				],
			],
		],
	],

	'menu_items' => [
		'admin' => [
			'system' => [
				'visitor_roles' => [
					'page_id' => 'visitors-roles',
					'index'   => 101,
				],
			],
		],
	],

];