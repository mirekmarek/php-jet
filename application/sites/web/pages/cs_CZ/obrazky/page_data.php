<?php
return [
	'id'                 => 'images',
	'order'              => 2,
	'name'               => 'Obrázky',
	'title'              => 'Obrázky',
	'menu_title'         => 'Obrázky',
	'breadcrumb_title'   => 'Obrázky',
	'layout_script_name' => 'default',
	'icon'               => 'images',
	'meta_tags'          => [
		[
			'attribute'       => 'name',
			'attribute_value' => 'description',
			'content'         => 'Obrázky'
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