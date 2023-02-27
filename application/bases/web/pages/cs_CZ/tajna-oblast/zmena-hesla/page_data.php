<?php
return [
	'id' => 'change-password',
	'name' => 'Změna hesla',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Změna hesla',
	'icon' => '',
	'menu_title' => 'Změna hesla',
	'breadcrumb_title' => 'Změna hesla',
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
			'module_name' => 'Login.Web',
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
