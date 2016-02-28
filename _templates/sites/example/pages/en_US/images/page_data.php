<?php
namespace Jet;

return [
    'ID' => 'images',
	'order' => 2,
    'name' => 'Images',
	'title' => 'Images',
	'menu_title' => 'Images',
	'breadcrumb_title' => 'Images',
	'layout_script_name' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
	'meta_tags' => [
		[
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Images'
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