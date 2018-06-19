<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Images',
	'description' => '',

	'pages' => [
		'admin' => [
			'images' => [
				'title'                  => 'Images',
				'relative_path_fragment' => 'images',
				'icon'                   => 'picture-o',
				'contents' => [
					[
						'controller_name' => 'Admin_Main',
						'controller_action' => 'default'
					]
				],
			],
			'dialog-select-image' => [
				'name'                   => 'Dialog - Select image',
				'title'                  => 'Select image',
				'relative_path_fragment' => 'select-image',
				'icon'                   => 'picture-o',
				'contents' => [
					[
						'controller_name' => 'Admin_Dialogs',
						'controller_action' => 'select_image'
					]
				],
			],
		],
		'rest' => [
			'images' => [
				'title'                  => 'Images',
				'relative_path_fragment' => 'gallery',
				'contents' => [
					[
						'controller_name' => 'REST_Main',
						'controller_action' => 'default'
					]
				],
			],

		],
	],

	'menu_items' => [
		'content' => [
			'images' => [
				'page_id' => 'images',
			],
		],
	],


];