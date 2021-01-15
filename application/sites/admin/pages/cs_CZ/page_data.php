<?php
return [
	'id'                 => '_homepage_',
	'name'               => 'Administrace - hlavní stránka',
	'is_active'          => true,
	'SSL_required'       => false,
	'title'              => 'Administrace',
	'icon'               => '',
	'menu_title'         => 'Administrace',
	'breadcrumb_title'   => 'Administrace',
	'is_secret'          => false,
	'http_headers'       => [
	],
	'layout_script_name' => 'default',
	'meta_tags'          => [
	],
	'contents'           => [
		[
			'module_name'           => 'UI.Admin',
			'controller_name'       => 'Main',
			'controller_action'     => 'default',
			'parameters'            => [
			],
			'output_position'       => '__main__',
			'output_position_order' => 1,
		],
	],
];
