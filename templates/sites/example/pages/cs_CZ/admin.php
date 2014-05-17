<?php
return array (
	'name' => 'Admin',
	'title' => 'Administrace - Rozcestník',
	'menu_title' => 'Administrace - Rozcestník',
	'breadcrumb_title' => 'Rozcestník',
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