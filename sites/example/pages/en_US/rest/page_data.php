<?php

return [
	'id' => 'rest',
	'name' => 'REST API',
	'title' => 'REST API - test',
	'layout_script_name' => 'plain',
	'is_admin_UI' => true,
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => 'JetExample.Test.REST',
			'controller_action' => 'test_rest',
		]

	]
];