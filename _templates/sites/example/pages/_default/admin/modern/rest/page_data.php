<?php
namespace Jet;

return [
    'ID' => 'admin/ria/rest_api',
    'service_type' => Mvc::SERVICE_TYPE_REST,
    'name' => 'Admin - REST API',
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