<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201701,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>', 'label' => 'Visitor users management',
	'types'  => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ], 'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/visitors-users' => [
			'title' => 'Visitors - Users', 'URL_fragment' => 'visitors-users', 'icon' => 'users',
		],
	],

	'admin_menu_items' => [
		'visitor_users' => [
			'label' => 'Visitors - Users', 'menu_id' => 'system', 'page_id' => 'admin/visitors-users',
			'icon'  => 'users',
		],
	],

];