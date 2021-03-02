<?php
return [
	'vendor' => 'Miroslav Marek <mirek.marek@web-jet.cz>',

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
				'icon'                   => 'file-alt',
				'contents'               => [
					[
						'controller_name'   => 'Admin',
						'controller_action' => 'default'
					]
				],
			],
		],
		'rest'  => [
			'articles' => [
				'title'                  => 'Articles',
				'relative_path_fragment' => 'article',
				'contents'               => [
					[
						'controller_name'   => 'REST',
						'controller_action' => 'get_article'
					]
				],
			],
		],
	],

	'menu_items' => [
		'admin' => [
			'content' => [
				'articles' => [
					'page_id' => 'articles',
				],
			]
		]
	],


];