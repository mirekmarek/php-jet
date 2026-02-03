<?php
return [
	'id' => 'validators-test',
	'name' => 'Test - Validators',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Test - Validators',
	'icon' => 'circle-check',
	'menu_title' => 'Test - Validators',
	'breadcrumb_title' => 'Test - Validators',
	'order' => 101,
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
			'module_name' => 'Test.Validators',
			'controller_name' => 'Main',
			'controller_action' => 'test_validators',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
		[
			'module_name' => 'Test.Validators',
			'controller_name' => 'Main',
			'controller_action' => 'test_validators_generated',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 2,
		],
	],
];
