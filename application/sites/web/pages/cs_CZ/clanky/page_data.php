<?php
return [
	'id'                 => 'articles',
	'order'              => 1,
	'name'               => 'Články',
	'title'              => 'Články',
	'menu_title'         => 'Články',
	'breadcrumb_title'   => 'Články',
	'icon'               => 'newspaper',
	'layout_script_name' => 'default',
	'meta_tags'          => [
		[
			'attribute'       => 'name',
			'attribute_value' => 'description',
			'content'         => 'Články'
		],
	],
	'contents'           => [
		[
			'module_name'           => 'Content.Articles',
			'controller_name'       => 'Web',
			'controller_action'     => 'default',
			'output_position_order' => 1
		]
	]
];