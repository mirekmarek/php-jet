<?php
namespace Jet;

return [
    'ID' => 'subpage_1',
    'name' => 'Podstránka 1',
	'title' => 'Podstránka 1',
	'menu_title' => 'Podstránka 1',
	'breadcrumb_title' => 'Podstránka 1',
	'layout_script_name' => 'default',
    'contents' => [
        [
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule',
            'controller_action' => 'test_action2',
            'output_position' => '',
            'output_position_required' => true,
            'output_position_order' => 1
        ],
        [
            'module_name' => 'JetExample.TestModule2',
            'controller_action' => 'test_action1',
            'output_position' => 'right',
            'output_position_required' => true,
            'output_position_order' => 1
        ],
        [
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule2',
            'controller_action' => 'test_action2',
            'output_position' => 'right',
            'output_position_required' => true,
            'output_position_order' => 2
        ],
    ]
];