<?php
return [
	'id' => 'sign-up',
	'name' => 'Registrace',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Registrace',
	'icon' => '',
	'menu_title' => 'Registrace',
	'breadcrumb_title' => 'Registrace',
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
	],
	'parameters' => [
		'do_not_display_in_menu' => '1',
	],
	'meta_tags' => [
	],
	'contents' => [
		[
			'module_name' => 'Web.Visitor.SignUp',
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
