<?php
return [
	'id' => 'control-centre',
	'name' => 'Control Centre',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Control Centre',
	'icon' => 'sliders',
	'menu_title' => 'Control Centre',
	'breadcrumb_title' => 'Control Centre',
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
