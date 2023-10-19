<?php
return [
	'id' => 'images',
	'name' => 'Images',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Images',
	'icon' => 'images',
	'menu_title' => 'Images',
	'breadcrumb_title' => 'Images',
	'order' => 2,
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
			'content' => 'Images',
		],
	],
	'contents' => [
		[
			'module_name' => 'Web.Images',
			'controller_name' => 'Main',
			'controller_action' => 'default',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
