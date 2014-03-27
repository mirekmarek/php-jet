<?php
return array (
	'name' => 'Admin - roles',
	'title' => 'Administrační rozhraní (klasické) - Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'menu_title' => 'Obrázky - Seznam',
	'URL_fragment' => 'obrazky',
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