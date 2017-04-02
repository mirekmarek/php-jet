<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,

	'vendor' => 'Jet (example)',
	'label' => 'Administrator roles management',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
	],

	'signals_callbacks' => [],

	'admin_sections' => [
		'admin/roles' => [
			'title' => 'User roles',
			'URL_fragment' => 'user-roles',
		]
	],

	'admin_menu_items' => [
		'roles' => [
			'label' => 'Roles',
			'parent_menu_id' => 'system',
			'page_id' => 'admin/roles',
			'icon' => 'lock'

		]
	]

];