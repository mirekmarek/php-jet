<?php
return [
    'ID' => 'admin/classic/images',
    'name' => 'Administrace - obrázky',
	'title' => 'Administrační rozhraní (klasické) - Obrázky',
	'breadcrumb_title' => 'Obrázky',
	'menu_title' => 'Obrázky',
	'meta_tags' => [],
	'contents' =>
			[
				[
                    'is_dynamic' => true,
					'module_name' => 'JetExample.Images',
                    'URL_parser_method_name' => 'parseRequestURL_Admin',
					'controller_action' => 'default',
					'output_position' => '',
					'output_position_required' => true,
					'output_position_order' => 1
				]
			]

];