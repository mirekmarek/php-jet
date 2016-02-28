<?php
return [
    'ID' => 'admin/classic/users',
	'name' => 'Administration - users',
	'title' => 'Administration (classic) - Users',
	'menu_title' => 'Users',
	'breadcrumb_title' => 'Users',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.AdminUsers',
                    'URL_parser_method_name' => 'parseRequestURL',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]

];