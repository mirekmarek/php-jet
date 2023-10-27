<?php
return [
	'id' => 'sign-up',
	'name' => 'Sign up',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Sign up',
	'icon' => '',
	'menu_title' => 'Sign up',
	'breadcrumb_title' => 'Sign up',
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
