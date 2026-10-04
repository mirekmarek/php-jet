<?php
return [
	'id' => 'ui-test',
	'name' => 'UI test',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'UI test',
	'icon' => 'cube',
	'menu_title' => 'UI test',
	'breadcrumb_title' => 'UI test',
	'order' => 103,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [],
	'parameters' => [],
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => 'Test.UI',
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
