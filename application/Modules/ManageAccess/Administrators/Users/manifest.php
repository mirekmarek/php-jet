<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek@web-jet.cz>',
	'label'       => 'Administrator users management',
	'description' => '',

	'ACL_actions' => [
		'get_user'    => 'Get user data',
		'add_user'    => 'Add new user',
		'update_user' => 'Update user',
		'delete_user' => 'Delete user',
	],


	'pages' => [
		'admin' => [
			'administrators-users' => [
				'title'                  => 'Administrators - Users',
				'icon'                   => 'user-secret',
				'relative_path_fragment' => 'administrators-users',
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
				'administrator_users' => [
					'separator_before' => true,
					'page_id'          => 'administrators-users',
					'index'            => 200,
				],
			],
		],
	],

];