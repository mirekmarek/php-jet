<?php
return array (
	'name' => 'Admin - articles',
	'title' => 'Administration Interface (classic) - Articles',
	'breadcrumb_title' => 'Articles',
	'menu_title' => 'Articles',
	'URL_fragment' => 'articles',
	'layout' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' =>
			array(
				array(
					'module_name' => 'JetExample\Articles',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)

);