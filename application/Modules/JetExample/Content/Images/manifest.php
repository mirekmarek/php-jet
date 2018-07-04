<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Images',
	'description' => '',

	'ACL_actions' => [
		'get_gallery'    => 'Get galley data',
		'add_gallery'    => 'Add new gallery',
		'update_gallery' => 'Update gallery',
		'delete_gallery' => 'Delete gallery',

		'add_image'    => 'Add new image',
		'update_image' => 'Update image',
		'delete_image' => 'Delete image',
	],

	'pages' => [
		'admin' => [
			'images' => [
				'title'                  => 'Images',
				'relative_path_fragment' => 'images',
				'icon'                   => 'picture-o',
				'contents' => [
					[
						'controller_name' => 'Admin',
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
						'controller_name' => 'REST',
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