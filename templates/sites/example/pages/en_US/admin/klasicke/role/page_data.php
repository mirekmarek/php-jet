<?php
namespace Jet;

return [
    'ID' => 'admin/classic/roles',
    'name' => 'Admin - roles',
	'title' => 'Administrační rozhraní (klasické) - Role',
	'menu_title' => 'Role',
	'breadcrumb_title' => 'Role - Seznam',
	'meta_tags' => array(),
	'contents' =>
			array(
				array(
                    'is_dynamic' => true,
					'module_name' => 'JetExample\AdminRoles',
                    'parser_URL_method_name' => 'parseRequestURL',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)

];