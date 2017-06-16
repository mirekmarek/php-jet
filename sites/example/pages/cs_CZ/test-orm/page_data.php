<?php
return [
	'id' => 'orm-test',
	'order' => 100,
    'name' => 'ORM test',
    'title' => 'Test ORM',
    'layout_script_name' => 'default',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'JetExample.Test.ORM',
		    'controller_action' => 'test_orm',
		    'output_position_order' => 1
	    ]
    ]
];

