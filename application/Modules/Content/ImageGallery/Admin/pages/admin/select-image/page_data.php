<?php
return [
	'id' => 'dialog-select-image',
	'name' => 'Dialog - Select image',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Select image',
	'icon' => 'images',
	'menu_title' => 'Select image',
	'breadcrumb_title' => 'Select image',
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => 'dialog',
	'http_headers' => [
	],
	'parameters' => [
	],
	'meta_tags' => [
	],
	'contents' => [
		[
			'module_name' => 'Content.ImageGallery.Admin',
			'controller_name' => 'DialogSelectImage',
			'controller_action' => 'default',
			'parameters' => [
			],
			'is_cacheable' => false,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
