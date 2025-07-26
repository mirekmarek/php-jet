<?php
return [
	'id' => 'subpage',
	'name' => 'Subpage',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Subpage',
	'icon' => 'file',
	'menu_title' => 'Subpage',
	'breadcrumb_title' => 'Subpage',
	'order' => 4,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
		'test value 1',
		'test value 2',
		'test value 3',
	],
	'parameters' => [
	],
	'meta_tags' => [
		[
			'attribute' => 'attribute',
			'attribute_value' => 'example',
			'content' => 'Example tag',
		],
		[
			'attribute' => 'Meta1attribute',
			'attribute_value' => 'Meta 1 attribute value',
			'content' => 'Meta 1 content',
		],
		[
			'attribute' => 'Meta2attribute',
			'attribute_value' => 'Meta 2 attribute value',
			'content' => 'Meta 2 content',
		],
		[
			'attribute' => 'Meta3attribute',
			'attribute_value' => 'Meta 3 attribute value',
			'content' => 'Meta 3 content',
		],
	],
	'contents' => [
		[
			'parameters' => [
				'text_id' => 'lorem',
			],
			'output' => [
				JetApplication\PageStaticContent::class,
				'get',
			],
			'is_cacheable' => false,
			'output_position' => '',
			'output_position_order' => 1,
		],
	],
];
