<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201701, 'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Images module', 'types' => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ],
	'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/images' => [
			'title' => 'Images', 'URL_fragment' => 'images', 'icon' => 'picture-o',
		],
	],

	'rest_api_hooks' => [
		'rest/images' => [
			'URL_fragment' => 'images',
		],
	],

	'admin_menu_items' => [
		'images' => [
			'label' => 'Images', 'menu_id' => 'content', 'page_id' => 'admin/images', 'icon' => 'picture-o',
		],
	],


];