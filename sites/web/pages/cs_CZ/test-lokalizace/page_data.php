<?php
return [
	'id' => 'test-locale',
	'order' => 101,
    'name' => 'Locale test',
    'title' => 'Test lokalizace',
    'layout_script_name' => 'default',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'Test.Locale',
		    'controller_action' => 'test_locale',
		    'output_position_order' => 1
	    ]
    ]
];

