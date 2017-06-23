<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Administrator roles management',
	'description' => '',

	'admin_sections' => [
		'administrators-roles' => [
			'title'                  => 'Administrators - User roles',
			'menu_title'             => 'Administrators - User roles',
			'breadcrumb_title'       => 'Administrators - User roles',
			'icon'                   => 'user-circle',
			'relative_path_fragment' => 'administrators-user-roles',
		],
	],

	'admin_menu_items' => [
		'administrator_roles' => [
			'menu_id' => 'system',
			'label'   => 'Administrators - User roles',
			'page_id' => 'administrators-roles',
			'icon'    => 'user-circle',
			'index'   => 201,
		],
	],

];