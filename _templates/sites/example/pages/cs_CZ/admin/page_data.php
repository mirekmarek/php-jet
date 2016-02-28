<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::getModuleInstance('JetExample.AdminUI');

return [
    'ID' => 'admin',
    'name' => 'Admin',
	'title' => 'Administrace - Rozcestník',
	'menu_title' => 'Administrace - Rozcestník',
	'breadcrumb_title' => 'Rozcestník',
    'custom_layouts_path' => $UI_module->getLayoutsDir(),
	'layout_script_name' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => [],
	'contents' => [
					[
						'module_name' => 'JetExample.AdminUI',
						'controller_action' => 'signpost',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					]
	]
];