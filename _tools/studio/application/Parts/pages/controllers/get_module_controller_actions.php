<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$GET = Http_Request::GET();

$module_name = $GET->getString( 'module' );
$controller = $GET->getString( 'controller' );


AJAX::commonResponse(
	[
		'actions' => Pages_Page::getModuleControllerActions( $module_name, $controller )
	]
);
