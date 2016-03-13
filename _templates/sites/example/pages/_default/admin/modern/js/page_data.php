<?php
use Jet\Mvc;

return [
    'ID' => 'admin/ria/js',
    'service_type' => Mvc::SERVICE_TYPE_STANDARD,
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