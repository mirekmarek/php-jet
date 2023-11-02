<?php
return [
	'id' => 'password-reset',
	'name' => 'Password reset',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Password reset',
	'icon' => '',
	'menu_title' => 'Password reset',
	'breadcrumb_title' => 'Password reset',
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
			'module_name' => 'Web.Visitor.PasswordReset',
			'controller_name' => 'Main',
			'controller_action' => 'enter_email',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
