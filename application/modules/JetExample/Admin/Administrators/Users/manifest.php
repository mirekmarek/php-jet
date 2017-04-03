<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',
	'label' => 'Administrator users management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
	],

	'signals_callbacks' => [],

	'admin_sections' => [
		'admin/administrators-users' => [
			'title' => 'Administrators - Users',
			'URL_fragment' => 'administrators-users',
		]
	],

	'admin_menu_items' => [
		'administrator_users' => [
			'label' => 'Administrators - Users',
			'parent_menu_id' => 'system',
			'page_id' => 'admin/administrators-users',
			'icon' => 'user'
		]
	]

];