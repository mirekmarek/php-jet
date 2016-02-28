<?php
return [
    'ID' => 'admin/classic/roles',
	'name' => 'Administrace - role',
	'title' => 'Administrační rozhraní (klasické) - Role',
	'breadcrumb_title' => 'Role',
	'menu_title' => 'Role',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.AdminRoles',
                    'URL_parser_method_name' => 'parseRequestURL',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]

];