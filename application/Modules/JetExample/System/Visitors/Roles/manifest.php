<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Visitor roles management',
	'description' => '',


	'admin_sections' => [
		'visitors-roles' => [
			'title'                  => 'Visitors - User roles',
			'relative_path_fragment' => 'visitors-user-roles',
			'icon'                   => 'street-view',
		],
	],

	'admin_menu_items' => [
		'visitor_roles' => [
			'label'   => 'Visitors - User roles',
			'menu_id' => 'system',
			'page_id' => 'visitors-roles',
			'icon'    => 'street-view',
			'index'   => 101,
		],
	],

];