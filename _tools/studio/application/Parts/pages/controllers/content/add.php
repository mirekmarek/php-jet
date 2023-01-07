<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$page = Pages::getCurrentPage();

$view = Application::getView();

$view->setVar( 'page', $page );

$ok = false;
$data = [];
$snippets = [];

if(
	$page &&
	($new_content = $page->catchContentCreateForm())
) {
	$page->addContent( $new_content );
	$form = $page->getContentCreateForm();

	if( $page->save() ) {
		$ok = true;

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


$snippets['content_create_form_area'] = $view->render( 'page/content/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);
