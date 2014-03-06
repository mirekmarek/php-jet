<?php
return array (
	'name' => 'Articles',
	'title' => 'Articles',
	'menu_title' => 'Articles',
	'breadcrumb_title' => 'Articles',
	'URL_fragment' => 'articles',
	'layout' => 'default',
	'headers_suffix' => '',
	'body_prefix' => '',
	'body_suffix' => '',
	'meta_tags' => array(
		array(
			'attribute'   => 'name',
			'attribute_value' => 'description',
			'content' => 'Articles'
		),
	),
	'contents' => array(
			array(
				'module_name' => 'JetExample\Articles',
				'controller_action' => 'default',
				'output_position' => '',
				'output_position_required' => true,
				'output_position_order' => 1
			)
		)
);