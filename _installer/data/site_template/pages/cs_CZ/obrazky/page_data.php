<?php
return [
    'id' => 'images',
	'order' => 3,
    'name' => 'Obrázky',
	'title' => 'Obrázky',
	'menu_title' => 'Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'layout_script_name' => 'default',
	'meta_tags' => [
		[
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Obrázky'
		],
	],
	'contents' => [
			[
				'module_name' => 'JetExample.Images',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_order' => 1
			]
	]
];