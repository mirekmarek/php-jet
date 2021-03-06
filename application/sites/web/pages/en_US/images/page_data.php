<?php
return [
	'id'                 => 'images',
	'order'              => 2,
	'name'               => 'Images',
	'title'              => 'Images',
	'menu_title'         => 'Images',
	'breadcrumb_title'   => 'Images',
	'layout_script_name' => 'default',
	'icon'               => 'images',
	'meta_tags'          => [
		[
			'attribute'       => 'name',
			'attribute_value' => 'description',
			'content'         => 'Images'
		],
	],
	'contents'           => [
		[
			'module_name'           => 'Content.Images',
			'controller_name'       => 'Web',
			'controller_action'     => 'default',
			'output_position_order' => 1
		]
	]
];