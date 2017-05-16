<?php
$content = require 'common_content.php';

return [
    'id' => 'subpage',
	'is_active' => true,
    'order' => 3,
    'name' => 'Subpage',
	'title' => 'Subpage',
	'menu_title' => 'Subpage',
	'breadcrumb_title' => 'Subpage',
	'layout_script_name' => 'default',
	'http_headers' => [
		'testHeader1' => 'test value 1',
		'testHeader2' => 'test value 2',
		'testHeader3' => 'test value 3',
	],

	'meta_tags' => [
			[
				'attribute'   => 'Meta1attribute',
				'attribute_value' => 'Meta 1 attribute value',
				'content' => 'Meta 1 content'
			],
			[
				'attribute'   => 'Meta2attribute',
				'attribute_value' => 'Meta 2 attribute value',
				'content' => 'Meta 2 content'
			],
			[
				'attribute'   => 'Meta3attribute',
				'attribute_value' => 'Meta 3 attribute value',
				'content' => 'Meta 3 content'
			],
	],
    'contents' => $content
];