<?php
return array (
	'name' => 'Admin',
	'title' => 'Administration Interface (RIA)',
	'menu_title' => 'Main Page',
	'breadcrumb_title' => 'Main Page',
	'URL_fragment' => 'ria',
	'layout' => 'ria',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\FrontController\Admin',
						'controller_action' => 'ria_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					)
			)
);