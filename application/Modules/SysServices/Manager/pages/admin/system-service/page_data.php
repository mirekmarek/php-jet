<?php
return [
	'id' => 'system-service',
	'name' => 'System services',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'System services',
	'icon' => 'gears',
	'menu_title' => '',
	'breadcrumb_title' => '',
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => '',
	'http_headers' => [
	],
	'parameters' => [
	],
	'meta_tags' => [
	],
	'contents' => [
		[
			'module_name' => '',
			'controller_name' => 'Main',
			'controller_action' => 'default',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '',
			'output_position_order' => 0,
		],
	],
];
