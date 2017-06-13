<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::moduleInstance( 'JetExample.AdminUI');

return [
	'id' => 'admin',
	'is_admin_UI' => true,
	'name' => 'Administration - main page',
	'title' => 'Administration',
	'custom_layouts_path' => $UI_module->getLayoutsDir(),
	'layout_script_name' => 'default',
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => 'JetExample.AdminUI',
			'controller_action' => 'default',
			'output_position_order' => 0
		]
	]
];