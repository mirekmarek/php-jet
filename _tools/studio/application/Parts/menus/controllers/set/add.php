<?php

namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\AJAX;


$form = Menus_MenuSet::getCreateForm();
$ok = false;
$data = [];

if( ($new_set = Menus_MenuSet::catchCreateForm()) ) {

	if( $new_set->save() ) {
		$ok = true;

		UI_messages::success(
			Tr::_( 'Menu set <strong>%set%</strong> has been created', [
				'set' => $new_set->getName()
			] )
		);

		$data = [
			'new_set' => $new_set->getName()
		];
	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}

AJAX::operationResponse(
	$ok,
	[
		'set_create_form_area' => Application::getView()->render( 'set/create/form' )
	],
	$data
);