<?php
return [
    'ID' => 'admin/classic/articles',
    'name' => 'Administration - articles',
	'title' => 'Administration (classic) - Articles',
	'breadcrumb_title' => 'Articles',
	'menu_title' => 'Articles',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.Articles',
                    'URL_parser_method_name' => 'parseRequestURL_Admin',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]
];