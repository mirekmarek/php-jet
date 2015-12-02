<?php
namespace Jet;

return [
    'ID' => 'admin/classic/images',
    'name' => 'Admin - roles',
	'title' => 'Administrační rozhraní (klasické) - Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'menu_title' => 'Obrázky - Seznam',
	'meta_tags' => array(),
	'contents' =>
			array(
				array(
                    'is_dynamic' => true,
					'module_name' => 'JetExample.Images',
                    'parser_URL_method_name' => 'parseRequestURL_Admin',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				)
			)

];