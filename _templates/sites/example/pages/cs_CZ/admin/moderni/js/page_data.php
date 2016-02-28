<?php
use Jet\Mvc;

return [
    'ID' => 'admin/ria/js',
    'service_type' => Mvc::SERVICE_TYPE_JET_JS,
	'name' => 'Administrace - JS rozhraní',
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