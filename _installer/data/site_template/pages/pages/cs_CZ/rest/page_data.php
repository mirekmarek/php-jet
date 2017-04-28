<?php
use Jet\Application_Modules;

$UI_module = Application_Modules::getModuleInstance('JetExample.AdminUI');

return [
	'id' => 'rest',
	'name' => 'REST API',
	'title' => 'REST API',
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