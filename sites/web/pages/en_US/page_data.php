<?php
use JetApplication\PageStaticContent;

return [
    'name' => 'Homepage',
    'title' => 'Homepage',
    'menu_title' => 'Homepage',
    'breadcrumb_title' => 'Homepage',
    'layout_script_name' => 'default',
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
		    'output_position_order' => 1,
		    'output' => [PageStaticContent::class, 'get']
	    ],
	    [
		    'output_position_order' => 2,
		    'output' => '<hr/>&copy; Miroslav Marek'
	    ]

    ]
];

