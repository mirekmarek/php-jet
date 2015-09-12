<?php
namespace Jet;

return [
    'ID' => 'admin/classic/articles',
    'name' => 'Admin - roles',
	'title' => 'Administrační rozhraní (klasické) - Články',
	'breadcrumb_title' => 'Články',
	'menu_title' => 'Články - Seznam',
	'meta_tags' => array(),
	'contents' =>
			array(
				array(
                    'is_dynamic' => true,
					'module_name' => 'JetExample\Articles',
                    'parser_URL_method_name' => 'parseRequestURL_Admin',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)
];