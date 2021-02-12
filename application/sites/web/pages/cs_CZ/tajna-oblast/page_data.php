<?php
return [
	'id' => 'secret_area',
	'name' => 'Tajná oblast',
	'is_active' => true,
	'SSL_required' => false,
	'title' => 'Tajná oblast',
	'icon' => '',
	'menu_title' => 'Tajná oblast',
	'breadcrumb_title' => 'Tajná oblast',
	'is_secret' => true,
	'http_headers' => [
	],
	'layout_script_name' => 'default-secret',
	'order' => 0,
	'meta_tags' => [
		[
			'attribute' => 'attribute',
			'attribute_value' => 'example',
			'content' => 'Example tag',
		],
		[
			'attribute' => 'name',
			'attribute_value' => 'robots',
			'content' => 'noindex',
		],
	],
	'contents' => [
		[
			'parameters' => [
			],
			'output' => '<h2>Tajná oblast</h2>
<p>Vítejte v tajné oblasti</p>',
			'is_cacheable' => false,
			'output_position' => '',
			'output_position_order' => 1,
		],
	],
];
