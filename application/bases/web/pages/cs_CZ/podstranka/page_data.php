<?php
$content = require 'common_content.php';

return [
	'id'                 => 'subpage',
	'order'              => 4,
	'name'               => 'Podstránka',
	'title'              => 'Podstránka',
	'menu_title'         => 'Podstránka',
	'breadcrumb_title'   => 'Podstránka',
	'layout_script_name' => 'default',
	'icon'               => 'file',
	'meta_tags'          => [
		[
			'attribute'       => 'Meta1attribute',
			'attribute_value' => 'Meta 1 attribute value',
			'content'         => 'Meta 1 content'
		],
		[
			'attribute'       => 'Meta2attribute',
			'attribute_value' => 'Meta 2 attribute value',
			'content'         => 'Meta 2 content'
		],
		[
			'attribute'       => 'Meta3attribute',
			'attribute_value' => 'Meta 3 attribute value',
			'content'         => 'Meta 3 content'
		],
	],
	'contents'           => $content
];