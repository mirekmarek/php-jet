<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::getModuleInstance('JetExample.AdminUI');

return [
    'id' => 'admin',
    'name' => 'Administration - signpost',
	'title' => 'Administration - Signpost',
	'menu_title' => 'Administration - Signpost',
	'breadcrumb_title' => 'Signpost',
    'custom_layouts_path' => $UI_module->getLayoutsDir(),
	'layout_script_name' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => [],
	'contents' => [
					[
						'module_name' => 'JetExample.AdminUI',
						'controller_action' => 'default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					]
	]
];