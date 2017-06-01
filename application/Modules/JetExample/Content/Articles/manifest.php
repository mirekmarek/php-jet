<?php
use Jet\Application_Module_Manifest;

return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Articles',
	'types'       => [ Application_Module_Manifest::MODULE_TYPE_GENERAL ],
	'description' => '',

	'require' => [],

	'admin_sections' => [
		'admin/articles' => [
			'title'                  => 'Articles',
			'relative_path_fragment' => 'articles',
			'icon'                   => 'file-text',
		],
	],

	'has_rest_api' => true,

	'admin_menu_items' => [
		'articles' => [
			'label'   => 'Articles',
			'menu_id' => 'content',
			'page_id' => 'admin/articles',
			'icon'    => 'file-text',
		],
	],


];