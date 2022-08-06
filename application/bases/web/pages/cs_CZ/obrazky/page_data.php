<?php
return [
	'id' => 'images',
	'name' => 'Obrázky',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Obrázky',
	'icon' => 'images',
	'menu_title' => 'Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'order' => 2,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
	],
	'parameters' => [
	],
	'meta_tags' => [
		[
			'attribute' => 'name',
			'attribute_value' => 'description',
			'content' => 'Obrázky',
		],
	],
	'contents' => [
		[
			'module_name' => 'Content.Images.Browser',
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
