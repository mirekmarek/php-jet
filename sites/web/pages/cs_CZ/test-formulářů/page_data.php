<?php
return [
	'id' => 'forms-test',
	'order' => 100,
    'name' => 'Forms test',
    'title' => 'Test formulářů',
    'layout_script_name' => 'default',
	'icon' => 'keyboard',
    'meta_tags' => [
    ],
    'contents' => [
	    [
		    'module_name' => 'Test.Forms',
		    'controller_action' => 'test_forms',
		    'output_position_order' => 1
	    ],
	    [
		    'module_name' => 'Test.Forms',
		    'controller_action' => 'test_forms_data_model',
		    'output_position_order' => 2
	    ],
    ]
];

