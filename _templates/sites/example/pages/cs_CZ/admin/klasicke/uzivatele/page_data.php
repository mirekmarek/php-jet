<?php
namespace Jet;

return [
    'ID' => 'admin/classic/users',
    'name' => 'Admin - users',
	'title' => 'Administrační rozhraní (klasické) - Uživatelé',
	'menu_title' => 'Uživatelé',
	'breadcrumb_title' => 'Uživatelé - Seznam',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.AdminUsers',
                    'parser_URL_method_name' => 'parseRequestURL',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]

];