<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>', 'label' => 'Visitor roles management',
	'types'  => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ], 'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/visitors-roles' => [
			'title' => 'Visitors - User roles', 'URL_fragment' => 'visitors-user-roles', 'icon' => 'street-view',
		],
	],

	'admin_menu_items' => [
		'visitor_roles' => [
			'label' => 'Visitors - User roles', 'menu_id' => 'system', 'page_id' => 'admin/visitors-roles',
			'icon'  => 'street-view',
		],
	],

];