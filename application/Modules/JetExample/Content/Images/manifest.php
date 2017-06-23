<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Images',
	'description' => '',

	'admin_sections' => [
		'images' => [
			'title'                  => 'Images',
			'relative_path_fragment' => 'images',
			'icon'                   => 'picture-o',
		],
	],

	'has_rest_api' => true,

	'admin_menu_items' => [
		'images' => [
			'label'   => 'Images',
			'menu_id' => 'content',
			'page_id' => 'images',
			'icon'    => 'picture-o',
		],
	],


];