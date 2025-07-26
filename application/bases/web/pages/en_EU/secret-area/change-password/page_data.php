<?php
return [
	'id' => 'change-password',
	'name' => 'Change password',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Change password',
	'icon' => '',
	'menu_title' => 'Change password',
	'breadcrumb_title' => 'Change password',
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => 'default-secret',
	'http_headers' => [
	],
	'parameters' => [
		'do_not_display_in_menu' => '1',
	],
	'meta_tags' => [
	],
	'contents' => [
		[
			'module_name' => 'Web.Auth.Login',
			'controller_name' => 'Main',
			'controller_action' => 'change_password',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
