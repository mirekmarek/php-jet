<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek@web-jet.cz>',
	'label'       => 'REST clients roles management',
	'description' => '',


	'ACL_actions' => [
		'get_role'    => 'Get role data',
		'add_role'    => 'Add new role',
		'update_role' => 'Update role',
		'delete_role' => 'Delete role',
	],


	'pages' => [
		'admin' => [
			'rest-clients-roles' => [
				'title'                  => 'REST clients - User roles',
				'icon'                   => 'lock',
				'relative_path_fragment' => 'rest-clients-user-roles',
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
				'rest_clients_roles' => [
					'page_id' => 'rest-clients-roles',
					'index'   => 301,
				],
			],
		],
	],

];