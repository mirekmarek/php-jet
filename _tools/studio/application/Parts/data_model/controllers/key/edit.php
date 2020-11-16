<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;
use Jet\Exception;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$key = $current->getCustomKey( Http_Request::GET()->getString('key') );

/**
 * @var DataModel_Definition_Key $key
 */
if(!$key) {
	Application::end();
}

$ok = false;
$data = [];
$snippets = [];


if( $key->catchEditForm() ) {

	$form = $key->getEditForm();

	if(DataModels::save($form)) {
		$ok = true;

		$form->setCommonMessage( UI_messages::createSuccess(Tr::_('Saved ...')) );
	}

}



$view = Application::getView();
$view->setVar('key', $key);

$snippets['key_detail_area_'.$key->getName()] = $view->render('model_edit/keys/list/item-body');
$snippets['key_header_area_'.$key->getName()] = $view->render('model_edit/keys/list/item-header');

AJAX::formResponse(
	$ok,
	$snippets,
	$data
);