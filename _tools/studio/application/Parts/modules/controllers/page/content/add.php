<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$module = Modules::getCurrentModule();
$page = Modules::getCurrentPage();

if(
	!$module ||
	!$page
) {
	die();
}
Application::setCurrentPart( 'pages' );

$ok = false;
$data = [];
$snippets = [];

$view = Application::getView();
$view->setVar( 'page', $page );

if(
($new_content = $page->catchContentCreateForm())
) {
	$form = $page->getContentCreateForm();

	$page->addContent( $new_content );

	if( $page->save() ) {
		$ok = true;
		$form = $page->getContentCreateForm();

		$form->setCommonMessage(
			UI_messages::createSuccess(
				Tr::_( 'New content has been created' )
			)
		);

		$snippets['content_list_area'] = $view->render( 'page/content/edit/form/list' );
	} else {
		$form->setCommonMessage( implode( '', UI_messages::get() ) );
	}


}
Modules::setupPageForms();

$snippets['content_create_form_area'] = $view->render( 'page/content/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);


