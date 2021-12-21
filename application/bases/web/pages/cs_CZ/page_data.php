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
	'order' => 0,
	'is_secret' => false,
	'layout_script_name' => 'default',
	'http_headers' => [
	],
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
			'module_name' => 'UI.Web',
			'controller_name' => 'Main',
			'controller_action' => 'homepage',
			'parameters' => [
			],
			'is_cacheable' => true,
			'output_position' => '__main__',
			'output_position_order' => 1,
		],
	],
];
