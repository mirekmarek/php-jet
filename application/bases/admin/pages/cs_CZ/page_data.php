<?php
return [
	'id' => '_homepage_',
	'name' => 'Administrace - hlavní stránka',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Administrace',
	'icon' => '',
	'menu_title' => 'Administrace',
	'breadcrumb_title' => 'Administrace',
	'order' => 0,
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
			'module_name' => 'UI.Admin',
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
