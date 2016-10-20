<?php
return [
    'name' => 'Homepage',
    'title' => 'Hlavní stránka',
    'menu_title' => 'Hlavní stránka',
    'breadcrumb_title' => 'Hlavní stránka',
    'layout_script_name' => 'default',
    'headers_suffix' => '<!-- Kód do hlavičky -->',
    'body_prefix' => '<!-- Kód na začátek stránky -->',
    'body_suffix' => '<!-- Kód na konec stránky -->',
    'meta_tags' => [
        [
            'attribute'   => 'Meta1attribute',
            'attribute_value' => 'Meta 1 attribute value',
            'content' => 'Meta 1 content'
        ],
        [
            'attribute'   => 'Meta2attribute',
            'attribute_value' => 'Meta 2 attribute value',
            'content' => 'Meta 2 content'
        ],
        [
            'attribute'   => 'Meta3attribute',
            'attribute_value' => 'Meta 3 attribute value',
            'content' => 'Meta 3 content'
        ],
    ],
    'contents' => [
        [
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule',
            'controller_action' => 'test_action2',
            'output_position' => '',
            'output_position_required' => true,
            'output_position_order' => 2
        ],
        [
            'is_dynamic' => true,
            'module_name' => 'JetExample.TestModule2',
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
    ]
];

