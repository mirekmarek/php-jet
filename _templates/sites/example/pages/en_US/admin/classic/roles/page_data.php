<?php
namespace Jet;

return [
    'ID' => 'admin/classic/roles',
	'name' => 'Administration - roles',
	'title' => 'Administration (classic) - Roles',
	'menu_title' => 'Roles',
	'breadcrumb_title' => 'Roles',
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