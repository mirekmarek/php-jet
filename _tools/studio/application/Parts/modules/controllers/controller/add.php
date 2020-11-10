<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$ok = false;

if(
	$current &&
	($new_controller=Modules_Module_Controller::catchCreateForm())
) {
	$current->addController( $new_controller );

	if( Modules::save() ) {
		$ok = true;
		UI_messages::success( Tr::_('Controller <b>%controller%</b> has been added', ['controller'=>$new_controller->getName()]) );

	}
}

$view = Application::getView();

$data = [];

$snippets = [
	'add_controller_form_area' => $view->render('modules/module_edit/add_controller_form')
];


AJAX::formResponse(
	$ok,
	$snippets,
	$data
);
