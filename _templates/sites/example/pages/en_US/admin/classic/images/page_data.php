<?php
namespace Jet;

return [
    'ID' => 'admin/classic/images',
	'name' => 'Administration - images',
	'title' => 'Administration (classic) - Images',
	'breadcrumb_title' => 'Images',
	'menu_title' => 'Images',
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