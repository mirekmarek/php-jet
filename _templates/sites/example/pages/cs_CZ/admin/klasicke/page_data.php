<?php
namespace Jet;

return [
    'ID' => 'admin/classic',
    'name' => 'Admin',
	'title' => 'Administrační rozhraní (klasické)',
	'menu_title' => 'Hlavní stránka',
	'breadcrumb_title' => 'Hlavní stránka',
	'layout_script_name' => 'default',
	'meta_tags' => [],
	'contents' => [
					[
                        'is_dynamic' => true,
						'module_name' => 'JetExample.AdminUI',
						'controller_action' => 'classic_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					]
	]
];