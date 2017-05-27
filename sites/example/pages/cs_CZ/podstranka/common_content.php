<?php
return [
	[
		'output_position_order' => 1,
		'output' => ['JetApplication\PageStaticContent', 'get'],
		'parameters' => [
			'text_id' => 'lorem'
		]
	],
	[
		'module_name' => 'JetExample.TestModule',
		'controller_action' => 'test_mvc_info',
		'output_position' => 'right',
		'output_position_order' => 1
	],
];