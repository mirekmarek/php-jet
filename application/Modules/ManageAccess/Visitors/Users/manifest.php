<?php
return [
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Visitor users management',
	'description' => '',


	'ACL_actions' => [
		'get_user'    => 'Get user data',
		'add_user'    => 'Add new user',
		'update_user' => 'Update user',
		'delete_user' => 'Delete user',
	],


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
		'admin' => [
			'system' => [
				'visitor_users' => [
					'page_id' => 'visitors-users',
					'index'   => 100,
				],
			],
		],
	],

];