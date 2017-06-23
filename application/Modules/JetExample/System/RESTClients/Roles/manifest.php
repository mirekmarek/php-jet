<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'REST clients roles management',
	'description' => '',

	'admin_sections' => [
		'rest-clients-roles' => [
			'title'                  => 'REST clients - User roles',
			'menu_title'             => 'REST clients - User roles',
			'breadcrumb_title'       => 'REST clients - User roles',
			'icon'                   => 'lock',
			'relative_path_fragment' => 'rest-clients-user-roles',
		],
	],

	'admin_menu_items' => [
		'rest_clients_roles' => [
			'menu_id' => 'system',
			'label'   => 'REST clients - User roles',
			'page_id' => 'rest-clients-roles',
			'icon'    => 'lock',
			'index'   => 301,
		],
	],

];