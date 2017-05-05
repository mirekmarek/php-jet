<?php
$content = require __DIR__.'/../../../common_content.php';

return [
	'id'          => 'subpage_3_2_1', 'name' => 'Podstránka 3-2-1', 'title' => 'Podstránka 3-2-1',
	'menu_title'  => 'Podstránka 3-2-1', 'breadcrumb_title' => 'Podstránka 3-2-1', 'meta_tags' => [
		[
			'attribute' => 'Meta1attribute', 'attribute_value' => 'Meta 1 attribute value',
			'content'   => 'Meta 1 content',
		], [
			'attribute' => 'Meta2attribute', 'attribute_value' => 'Meta 2 attribute value',
			'content'   => 'Meta 2 content',
		], [
			'attribute' => 'Meta3attribute', 'attribute_value' => 'Meta 3 attribute value',
			'content'   => 'Meta 3 content',
		],
	], 'contents' => $content,
];