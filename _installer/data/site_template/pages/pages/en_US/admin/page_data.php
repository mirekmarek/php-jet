<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::getModuleInstance('JetExample.AdminUI');

return [
	'id' => 'admin',
	'is_admin_UI' => true,
	'name' => 'Administration',
	'title' => 'Administration',
	'custom_layouts_path' => $UI_module->getLayoutsDir(),
	'layout_script_name' => 'default',
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => $UI_module->getModuleManifest()->getName(),
			'controller_action' => 'default',
			'output_position' => '',
			'output_position_required' => true,
			'output_position_order' => 0
		]
	]
];