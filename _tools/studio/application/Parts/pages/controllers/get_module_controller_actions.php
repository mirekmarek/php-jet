<?php
namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;

$GET = Http_Request::GET();

$actions = [];


$module_name = $GET->getString('module');
$controller = $GET->getString('controller');

if(Modules::exists($module_name)) {
	$module = Modules::getModule($module_name);
	$controllers = $module->getControllers();

	if(isset($controllers[$controller])) {
		$actions = $module->getControllerAction($controller);
	}
}


AJAX::response(
	[
		'actions' => $actions
	]
);
