<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$current = Modules::getCurrentModule();

$ok = false;
$data = [];

if(
	$current &&
	($new_page = $current->getPages()->catchCratePageForm())
) {
	$form = $current->getPages()->getPageCreateForm();

	if( $new_page->save() ) {
		$ok = true;
		UI_messages::success( Tr::_( 'Page <b>%page%</b> has been added', ['page' => $new_page->getName()] ) );
		$data['id'] = $new_page->getFullId();
	} else {
		$form->setCommonMessage( implode( '', UI_messages::get() ) );
	}
}

$snippets = [
	'add_page_form_area' => Application::getView()->render( 'page/create/form' )
];

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);
