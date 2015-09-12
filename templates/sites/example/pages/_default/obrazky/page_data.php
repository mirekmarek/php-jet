<?php
namespace Jet;

return [
    'ID' => 'images',
    'name' => 'Images',
	'title' => 'Obrázky',
	'menu_title' => 'Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'layout_script_name' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
	'meta_tags' => array(
		array(
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Images'
		),
	),
	'contents' => array(
			array(
				'module_name' => 'JetExample\Images',
                'parser_URL_method_name' => 'parseRequestURL_Public',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			)
		)
];