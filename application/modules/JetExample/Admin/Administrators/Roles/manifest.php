<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label' => 'Administrator roles management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
	],

	'signals_callbacks' => [],

	'admin_sections' => [
		'admin/administrators-roles' => [
			'title' => 'Administrators - User roles',
			'URL_fragment' => 'administrators-user-roles',
		]
	],

	'admin_menu_items' => [
		'administrator_roles' => [
			'label' => 'Administrators - User roles',
			'parent_menu_id' => 'system',
			'page_id' => 'admin/administrators-roles',
			'icon' => 'user-circle'
		]
	]

];