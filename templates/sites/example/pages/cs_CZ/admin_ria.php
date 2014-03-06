<?php
return array (
	'name' => 'Admin',
	'title' => 'Administrační rozhraní (moderní)',
	'menu_title' => 'Hlavní stránka',
	'breadcrumb_title' => 'Hlavní stránka',
	'URL_fragment' => 'moderni',
	'layout' => 'ria',
	'is_admin_UI' => true,
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\AdminUIManager',
						'controller_action' => 'ria_default',
						'output_position' => '',
						'output_position_required' => true,
						'output_position_order' => 0
					)
			)
);