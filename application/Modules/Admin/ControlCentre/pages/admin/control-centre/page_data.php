<?php
return [
	'id' => 'control-centre',
	'name' => 'Control Center',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Control Center',
	'icon' => 'sliders',
	'menu_title' => 'Control Center',
	'breadcrumb_title' => 'Control Center',
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
			'module_name' => 'Admin.ControlCentre',
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
