<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();

$ok = false;
if(
	$current &&
	($controller_id=$GET->getString('controller')) &&
	( $controller=$current->getController( $controller_id ) )
) {


	if($controller->catchEditForm( $current )) {
		$form = $controller->getEditForm( $current );

		if( Modules::save( $form ) ) {
			$ok = true;
			$form->setCommonMessage(
				UI_messages::createSuccess( Tr::_('Saved ...', []) )
			);
		}
	}

	$view = Application::getView();
	$view->setVar('controller', $controller);

	AJAX::formResponse(
		$ok,
		[
			'controller_'.$controller->getInternalId().'_head' => $view->render('module_edit/controllers/item-head'),
			'controller_'.$controller->getInternalId() => $view->render('module_edit/controllers/item-body')
		]
	);

}
