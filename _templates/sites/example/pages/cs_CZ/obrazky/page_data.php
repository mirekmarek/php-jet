<?php
namespace Jet;

return [
    'ID' => 'images',
    'name' => 'Obrázky',
	'title' => 'Obrázky',
	'menu_title' => 'Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'layout_script_name' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
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
                'URL_parser_method_name' => 'parseRequestURL_Public',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			]
	]
];