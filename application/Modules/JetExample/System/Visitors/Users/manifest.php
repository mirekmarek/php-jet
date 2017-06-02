<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Visitor users management',
	'description' => '',


	'admin_sections' => [
		'admin/visitors-users' => [
			'title'                  => 'Visitors - Users',
			'relative_path_fragment' => 'visitors-users',
			'icon'                   => 'users',
		],
	],

	'admin_menu_items' => [
		'visitor_users' => [
			'label'   => 'Visitors - Users',
			'menu_id' => 'system',
			'page_id' => 'admin/visitors-users',
			'icon'    => 'users',
			'index'   => 100,
		],
	],

];