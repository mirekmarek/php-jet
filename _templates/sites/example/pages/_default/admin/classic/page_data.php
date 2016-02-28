<?php
return [
    'ID' => 'admin/classic',
	'name' => 'Administration (classic)',
	'title' => 'Administration (classic)',
	'menu_title' => 'Main page',
	'breadcrumb_title' => 'Main page',
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