<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Articles',
	'description' => '',

	'admin_sections' => [
		'articles' => [
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
			'page_id' => 'articles',
			'icon'    => 'file-text',
		],
	],


];