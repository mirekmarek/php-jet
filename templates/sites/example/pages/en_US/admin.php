<?php
return array (
	'name' => 'Admin',
	'title' => 'Administration Interface - Signpost',
	'menu_title' => 'Administration Interface - Signpost',
	'breadcrumb_title' => 'Signpost',
	'URL_fragment' => 'admin',
	'layout' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\AdminFrontController',
						'controller_action' => 'signpost',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					)
			)
);