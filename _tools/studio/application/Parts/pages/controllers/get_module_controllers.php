<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$GET = Http_Request::GET();

$default_controller = '';
$actions = [];
$module_name = $GET->getString( 'module' );

$controllers = Pages_Page::getModuleControllers( $module_name );

foreach( $controllers as $default_controller ) {
	$actions = Pages_Page::getModuleControllerActions( $module_name, $default_controller );
	break;
}

AJAX::commonResponse(
	[
		'controllers'        => $controllers,
		'default_controller' => $default_controller,
		'actions'            => $actions
	]
);
