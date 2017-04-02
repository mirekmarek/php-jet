<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Administrator users management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
	],

	'signals_callbacks' => [],

	'admin_sections' => [
		'admin/users' => [
			'title' => 'Users',
			'URL_fragment' => 'users',
		]
	],

	'admin_menu_items' => [
		'users' => [
			'label' => 'Users',
			'parent_menu_id' => 'system',
			'page_id' => 'admin/users',
			'icon' => 'user'
		]
	]

];