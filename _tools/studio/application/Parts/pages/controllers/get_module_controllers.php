<?php
namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$GET = Http_Request::GET();

$controllers = [];
$default_controller = '';
$actions = [];


$module_name = $GET->getString('module');

if(Modules::exists($module_name)) {
	$module = Modules::getModule($module_name);
	$controllers = $module->getControllers();

	foreach($controllers as $default_controller) {
		break;
	}

	if($default_controller) {
		$actions = $module->getControllerAction($default_controller);
	}
}


AJAX::response(
	[
		'controllers' => $controllers,
		'default_controller' => $default_controller,
		'actions' => $actions
	]
);
