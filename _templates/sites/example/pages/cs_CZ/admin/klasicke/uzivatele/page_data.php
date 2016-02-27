<?php
namespace Jet;

return [
    'ID' => 'admin/classic/users',
	'name' => 'Administrace - uživatelé',
	'title' => 'Administrační rozhraní (klasické) - Uživatelé',
	'breadcrumb_title' => 'Uživatelé',
	'menu_title' => 'Uživatelé',
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