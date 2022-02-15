<?php
return [
	'id' => 'test',
	'name' => 'test',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'test',
	'icon' => '',
	'menu_title' => 'test',
	'breadcrumb_title' => 'test',
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
		'df gdf gd',
		'dfs gsdfg dfs gdf',
	],
	'parameters' => [
	],
	'meta_tags' => [
		[
			'attribute' => 'meta1',
			'attribute_value' => 'meta1v',
			'content' => 'meta1content',
		],
		[
			'attribute' => 'meta2',
			'attribute_value' => 'meta2v',
			'content' => 'meta2content',
		],
	],
	'contents' => [
		[
			'parameters' => [
			],
			'output' => [
				'JetApplication\\PageStaticContent',
				'get',
			],
			'is_cacheable' => true,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
