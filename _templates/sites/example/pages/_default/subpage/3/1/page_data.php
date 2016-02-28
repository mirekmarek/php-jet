<?php
$content = require __DIR__.'/../../common_content.php';

return [
    'ID' => 'subpage/3/1',
    'order' => 1,
    'name' => 'Subpage 3-1',
	'title' => 'Subpage 3-1',
	'menu_title' => 'Subpage 3-1',
	'breadcrumb_title' => 'Subpage 3-1',
	'layout_script_name' => 'default',
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