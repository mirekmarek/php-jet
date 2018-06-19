<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Articles',
	'description' => '',

	'pages' => [
		'admin' => [
			'articles' => [
				'title'                  => 'Articles',
				'relative_path_fragment' => 'articles',
				'icon'                   => 'file-text',
				'contents' => [
					[
						'controller_name' => 'Admin_Main',
						'controller_action' => 'default'
					]
				],
			],
		],
		'rest' => [
			'articles' => [
				'title'                  => 'Articles',
				'relative_path_fragment' => 'article',
				'contents' => [
					[
						'controller_name' => 'REST_Main',
						'controller_action' => 'get_article'
					]
				],
			],
		],
	],

	'menu_items' => [
		'content' => [
			'articles' => [
				'page_id' => 'articles',
			],
		]
	],


];