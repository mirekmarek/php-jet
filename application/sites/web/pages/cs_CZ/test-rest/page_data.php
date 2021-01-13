<?php

return [
	'id' => 'rest',
	'order' => 103,
	'name' => 'REST API test',
	'title' => 'Test REST API',
	'layout_script_name' => 'plain',
	'icon' => 'network-wired',
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => 'Test.REST',
			'controller_action' => 'test_rest',
		]

	]
];