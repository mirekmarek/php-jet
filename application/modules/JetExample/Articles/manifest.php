<?php
use Jet\Application_Modules_Module_Manifest;

return [
	'API_version' => 201401,
	'vendor' => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label' => 'Basic acticles module',
	'types' => [Application_Modules_Module_Manifest::MODULE_TYPE_GENERAL],
	'description' => '',

	'require' => [
	],

	'admin_sections' => [
		'admin/articles' => [
			'title' => 'Articles',
			'URL_fragment' => 'articles',
		]
	],

	'admin_menu_items' => [
		'articles' => [
			'label' => 'Articles',
			'parent_menu_id' => 'content',
			'page_id' => 'admin/articles',
			'icon' => 'file-text'
		]
	]

];