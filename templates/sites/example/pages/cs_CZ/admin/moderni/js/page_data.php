<?php
namespace Jet;

return [
    'ID' => 'admin/ria/js',
    'service_type' => Mvc::SERVICE_TYPE_JET_JS,
    'name' => 'Admin - Jet JavaScript Service',
	'title' => ' ',
	'menu_title' => ' ',
	'breadcrumb_title' => ' ',
	'meta_tags' => array(),
	'contents' => array(
					array(
						'module_name' => 'JetExample\AdminUI',
                        'parser_URL_method_name' => 'parseRequestURL',
						'controller_action' => 'default',
					)
			)
];