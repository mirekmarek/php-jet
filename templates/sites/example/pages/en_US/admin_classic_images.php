<?php
return array (
	'name' => 'Admin - images',
	'title' => 'Administration Interface (classic) - Images',
	'breadcrumb_title' => 'Images',
	'menu_title' => 'Images',
	'URL_fragment' => 'articles',
	'layout' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' =>
			array(
				array(
					'module_name' => 'JetExample\Images',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)

);