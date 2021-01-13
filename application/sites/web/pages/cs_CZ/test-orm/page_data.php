<?php
return [
	'id' => 'orm-test',
	'order' => 102,
    'name' => 'ORM test',
    'title' => 'Test ORM',
    'layout_script_name' => 'default',
	'icon' => 'database',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'Test.ORM',
		    'controller_action' => 'test_orm',
		    'output_position_order' => 1
	    ]
    ]
];

