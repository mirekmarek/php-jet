<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201701,

	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>', 'label' => 'Administrator roles management',
	'types'  => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ], 'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/administrators-roles' => [
			'title'            => 'Administrators - User roles', 'menu_title' => 'Administrators - User roles',
			'breadcrumb_title' => 'Administrators - User roles', 'icon' => 'user-circle',
			'URL_fragment'     => 'administrators-user-roles',
		],
	],

	'admin_menu_items' => [
		'administrator_roles' => [
			'menu_id' => 'system',
			'label'   => 'Administrators - User roles',
			'page_id' => 'admin/administrators-roles',
			'icon' => 'user-circle',
		],
	],

];