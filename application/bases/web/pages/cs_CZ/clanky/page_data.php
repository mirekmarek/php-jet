<?php
return [
	'id' => 'articles',
	'name' => 'Články',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Články',
	'icon' => 'newspaper',
	'menu_title' => 'Články',
	'breadcrumb_title' => 'Články',
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
			'content' => 'Články',
		],
	],
	'contents' => [
		[
			'module_name' => 'Web.Articles',
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
