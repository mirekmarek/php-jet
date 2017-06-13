<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::moduleInstance( 'JetExample.AdminUI');

return [
	'id' => 'admin',
	'name' => 'Administrace - hlavní stránka',
	'title' => 'Administrace',
	'custom_layouts_path' => $UI_module->getLayoutsDir(),
	'layout_script_name' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => [],
	'contents' => [
		[
			'module_name' => 'JetExample.AdminUI',
			'controller_action' => 'default',
			'output_position_order' => 0
		]
	]
];