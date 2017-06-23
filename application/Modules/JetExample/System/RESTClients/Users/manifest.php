<?php
return [
	'API_version' => 201701,

	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label'       => 'REST clients users management',
	'description' => '',

	'admin_sections' => [
		'rest-clients-users' => [
			'title'                  => 'REST clients - Users',
			'breadcrumb_title'       => 'REST clients - Users',
			'icon'                   => 'server',
			'relative_path_fragment' => 'rest-clients-users',
		],
	],

	'admin_menu_items' => [
		'rest_clients_users' => [
			'label'            => 'REST clients - Users',
			'separator_before' => true,
			'menu_id'          => 'system',
			'page_id'          => 'rest-clients-users',
			'icon'             => 'server',
			'index'            => 300,
		],
	],

];