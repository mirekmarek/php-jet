<?php
namespace Jet;

return [
    'ID' => 'articles',
	'order' => 1,
    'name' => 'Články',
	'title' => 'Články',
	'menu_title' => 'Články',
	'breadcrumb_title' => 'Články',
	'layout_script_name' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
	'meta_tags' => [
		[
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Články'
		],
	],
	'contents' => [
			[
				'module_name' => 'JetExample.Articles',
                'URL_parser_method_name' => 'parseRequestURL_Public',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			]
	]
];