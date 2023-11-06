<?php

namespace JetStudio;

use Exception;
use Jet\AJAX;
use Jet\UI_messages;
use Jet\Tr;

$current = Modules::getCurrentModule();

if(!$current) {
	die();
}

$view = Application::getView();


if(!($new=$current->catchCloneForm())) {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
	
	AJAX::operationResponse(false, [
		'clone_module_form_area' => $view->render('module/clone/form')
	]);
}

try {
	Modules::clone( $current, $new );
} catch( Exception $e ) {
	Application::handleError( $e );
	
	AJAX::operationResponse(false, [
		'clone_module_form_area' => $view->render('module/clone/form')
	]);
}

UI_messages::success(Tr::_('Module %new_module_name% has been cloned', ['new_module_name'=>$new->getName()]));


AJAX::operationResponse(success: true, data: [
	'redirect' => Modules::getActionUrl(action: '', custom_get_params: ['module'=>$new->getName()])
]);

