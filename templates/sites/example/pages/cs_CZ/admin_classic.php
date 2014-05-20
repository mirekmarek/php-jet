<?php
return array (
	'name' => 'Admin',
	'title' => 'Administrační rozhraní (klasické)',
	'menu_title' => 'Hlavní stránka',
	'breadcrumb_title' => 'Hlavní stránka',
	'URL_fragment' => 'klasicke',
	'layout' => 'default',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\FrontController\Admin',
						'controller_action' => 'classic_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					)
			)
);