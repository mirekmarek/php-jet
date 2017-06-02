<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'Administrator users management',
	'description' => '',

	'admin_sections' => [
		'admin/administrators-users' => [
			'title'                  => 'Administrators - Users',
			'breadcrumb_title'       => 'Administrators - Users',
			'icon'                   => 'user-secret',
			'relative_path_fragment' => 'administrators-users',
		],
	],

	'admin_menu_items' => [
		'administrator_users' => [
			'label'            => 'Administrators - Users',
			'separator_before' => true,
			'menu_id'          => 'system',
			'page_id'          => 'admin/administrators-users',
			'icon'             => 'user-secret',
			'index'            => 200,
		],
	],

];