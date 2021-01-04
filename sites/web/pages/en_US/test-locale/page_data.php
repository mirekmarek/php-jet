<?php
return [
	'id' => 'test-locale',
	'order' => 101,
    'name' => 'Test - Locale',
    'title' => 'Test - Locale',
    'layout_script_name' => 'default',
	'icon' => 'globe',
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

