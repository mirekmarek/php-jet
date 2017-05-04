<?php
use Jet\Application_Modules_Module_Manifest;
return [
	'API_version' => 201401,
	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'Images module',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/images' => [
			'title' => 'Images',
			'URL_fragment' => 'images',
			'icon' => 'picture-o'
		]
	],

	'rest_api_hooks' => [
		'rest/images' => [
			'URL_fragment' => 'images',
		],
	],

	'admin_menu_items' => [
		'images' => [
			'label' => 'Images',
			'parent_menu_id' => 'content',
			'page_id' => 'admin/images',
			'icon' => 'picture-o'
		]
	]


];