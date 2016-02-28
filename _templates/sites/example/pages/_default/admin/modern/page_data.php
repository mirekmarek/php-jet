<?php
return [
    'ID' => 'admin/ria',
	'name' => 'Administration (modern)',
	'title' => 'Administration (modern)',
	'menu_title' => 'Main page',
	'breadcrumb_title' => 'Main page',
	'layout_script_name' => 'ria',
    'layout_initializer_module_name' => 'JetExample.AdminUI',
	'meta_tags' => [],
	'contents' => [
					[
						'module_name' => 'JetExample.AdminUI',
						'controller_action' => 'ria_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					]
	]
];