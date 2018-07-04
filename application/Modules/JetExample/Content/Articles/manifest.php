<?php
return [
	'API_version' => 201701,
	'vendor'      => 'Miroslav Marek <mirek.marek.2m@gmail.com>',

	'label'       => 'Articles',
	'description' => '',

	'ACL_actions' => [
		'get_article'    => 'Get article data',
		'add_article'    => 'Add new article',
		'update_article' => 'Update article',
		'delete_article' => 'Delete article',
	],

	'pages' => [
		'admin' => [
			'articles' => [
				'title'                  => 'Articles',
				'relative_path_fragment' => 'articles',
				'icon'                   => 'file-text',
				'contents' => [
					[
						'controller_name' => 'Admin',
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
						'controller_name' => 'REST',
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