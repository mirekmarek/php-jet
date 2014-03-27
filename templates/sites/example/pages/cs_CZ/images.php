<?php
return array (
	'name' => 'Images',
	'title' => 'Obrázky',
	'menu_title' => 'Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'URL_fragment' => 'obrazky',
	'layout' => 'default',
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
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			)
		)
);