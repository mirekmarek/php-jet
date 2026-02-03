<?php
return [
	'id' => 'input-catchers-test',
	'name' => 'Test - Input catchers',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Test - Input Catchers',
	'icon' => 'arrows-to-circle',
	'menu_title' => 'Test - Input Catchers',
	'breadcrumb_title' => 'Test - Input Catchers',
	'order' => 102,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
	],
	'parameters' => [
	],
	'meta_tags' => [
	],
	'contents' => [
		[
			'module_name' => 'Test.InputCatchers',
			'controller_name' => 'Main',
			'controller_action' => 'test_input_catchers',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
		[
			'module_name' => 'Test.InputCatchers',
			'controller_name' => 'Main',
			'controller_action' => 'test_input_catchers_generated',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 2,
		],
	],
];
