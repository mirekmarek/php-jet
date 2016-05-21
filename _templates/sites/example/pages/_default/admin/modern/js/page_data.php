<?php

return [
    'ID' => 'admin/ria/js',
    'service_type' => 'JetJS',
    'name' => 'Admin - Jet JavaScript Service',
	'title' => '',
	'menu_title' => '',
	'breadcrumb_title' => '',
	'meta_tags' => [],
	'contents' => [
					[
						'module_name' => 'JetExample.AdminUI',
                        'URL_parser_method_name' => 'parseRequestURL',
						'controller_action' => 'default',
					]
	]
];