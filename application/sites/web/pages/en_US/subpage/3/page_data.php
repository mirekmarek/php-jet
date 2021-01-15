<?php

use JetApplication\PageStaticContent;

return [
	'id'                 => 'subpage_3',
	'order'              => 3,
	'name'               => 'Subpage 3',
	'is_active'          => true,
	'SSL_required'       => false,
	'title'              => 'Subpage 3',
	'icon'               => '',
	'menu_title'         => 'Subpage 3',
	'breadcrumb_title'   => 'Subpage 3',
	'is_secret'          => false,
	'http_headers'       => [
		'testHeader1' => 'test value 1',
		'testHeader2' => 'test value 2',
		'testHeader3' => 'test value 3',
	],
	'layout_script_name' => 'default',
	'meta_tags'          => [
		[
			'attribute'       => 'attribute',
			'attribute_value' => 'example',
			'content'         => 'Example tag',
		],
		[
			'attribute'       => 'Meta1attribute',
			'attribute_value' => 'Meta 1 attribute value',
			'content'         => 'Meta 1 content',
		],
		[
			'attribute'       => 'Meta2attribute',
			'attribute_value' => 'Meta 2 attribute value',
			'content'         => 'Meta 2 content',
		],
		[
			'attribute'       => 'Meta3attribute',
			'attribute_value' => 'Meta 3 attribute value',
			'content'         => 'Meta 3 content',
		],
	],
	'contents'           => [
		[
			'parameters'            => [
				'text_id' => 'lorem',
			],
			'output'                => [
				PageStaticContent::class,
				'get'
			],
			'output_position'       => '',
			'output_position_order' => 1,
		],
	],
];
