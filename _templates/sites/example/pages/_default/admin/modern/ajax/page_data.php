<?php
namespace Jet;

return [
    'ID' => 'admin/ria/ajax',
    'service_type' => Mvc::SERVICE_TYPE_AJAX,
    'name' => 'Admin - AJAX service',
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