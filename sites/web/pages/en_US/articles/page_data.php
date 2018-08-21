<?php
return [
    'id' => 'articles',
	'order' => 3,
    'name' => 'Articles',
	'title' => 'Articles',
	'menu_title' => 'Articles',
	'breadcrumb_title' => 'Articles',
	'layout_script_name' => 'default',
	'meta_tags' => [
		[
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Articles'
		],
	],
	'contents' => [
			[
				'module_name' => 'Content.Articles',
				'controller_name' => 'Web',
				'controller_action' => 'default',
				'output_position_order' => 1
			]
	]
];