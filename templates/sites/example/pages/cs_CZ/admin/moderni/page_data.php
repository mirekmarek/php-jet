<?php
namespace Jet;

return [
    'ID' => 'admin/ria',
    'name' => 'Admin',
	'title' => 'Administrační rozhraní (moderní)',
	'menu_title' => 'Hlavní stránka',
	'breadcrumb_title' => 'Hlavní stránka',
	'layout_script_name' => 'ria',
    'layout_initializer_module_name' => 'JetExample\AdminUI',
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\AdminUI',
						'controller_action' => 'ria_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					)
			)
];