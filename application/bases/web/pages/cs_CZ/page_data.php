<?php
return [
	'id' => '_homepage_',
	'name' => 'Homepage',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Hlavní stránka',
	'icon' => 'home',
	'menu_title' => 'Hlavní stránka',
	'breadcrumb_title' => 'Hlavní stránka',
	'is_secret' => false,
	'http_headers' => [
	],
	'layout_script_name' => 'default',
	'order' => 0,
	'parameters' => [
	],
	'meta_tags' => [
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
			],
			'output' => [
				'JetApplication\\PageStaticContent',
				'get',
			],
			'is_cacheable' => false,
			'output_position' => '',
			'output_position_order' => 1,
		],
		[
			'parameters' => [
			],
			'output' => '<hr/>&copy; Miroslav Marek',
			'is_cacheable' => false,
			'output_position' => '',
			'output_position_order' => 2,
		],
	],
];
