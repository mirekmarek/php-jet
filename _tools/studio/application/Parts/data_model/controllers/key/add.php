<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;

$current = DataModels::getCurrentModel();

$ok = false;
$data = [];
$snippets = [];

if( ($new_key = DataModel_Definition_Key::catchCreateForm()) ) {

	$form = DataModel_Definition_Key::getCreateForm();

	if( $current->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Key <strong>%key%</strong> has been created', [
				'key' => $new_key->getName()
			] )
		);
	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}

$snippets['key_add_form_area'] = Application::getView()->render( 'key/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);