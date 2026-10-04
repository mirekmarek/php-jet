<?php
return [
	'id' => 'articles',
	'name' => 'Articles',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Articles',
	'icon' => 'newspaper',
	'menu_title' => 'Articles',
	'breadcrumb_title' => 'Articles',
	'order' => 1,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
	],
	'parameters' => [
	],
	'meta_tags' => [
		[
			'attribute' => 'name',
			'attribute_value' => 'description',
			'content' => 'Articles',
		],
	],
	'contents' => [
		[
			'module_name' => 'Content.Articles.Web',
			'controller_name' => 'Main',
			'controller_action' => 'list',
			'parameters' => [
			],
			'is_cacheable' => true,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
