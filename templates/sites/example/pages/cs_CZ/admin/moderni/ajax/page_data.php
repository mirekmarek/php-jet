<?php
namespace Jet;

return [
    'ID' => 'admin/ria/ajax',
    'service_type' => Mvc::SERVICE_TYPE_AJAX,
    'name' => 'Admin - AJAX service',
	'title' => ' ',
	'menu_title' => ' ',
	'breadcrumb_title' => ' ',
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample.AdminUI',
                        'parser_URL_method_name' => 'parseRequestURL',
						'controller_action' => 'default',
					)
			)
];