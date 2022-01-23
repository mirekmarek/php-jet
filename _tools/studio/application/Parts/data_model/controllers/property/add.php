<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentClass();

$ok = false;
$data = [];
$snippets = [];


if( ($new_property = DataModel_Definition_Property::catchCreateForm( $current )) ) {

	$form = DataModel_Definition_Property::getCreateForm();
	$form->getField( 'type' )->setDefaultValue( $new_property->getType() );

	if( $new_property->add( $current ) ) {

		UI_messages::success(
			Tr::_( 'Property <strong>%property%</strong> has been created', [
				'property' => $new_property->getName()
			] )
		);

		$ok = true;
		$data['location'] = Http_Request::currentURI( ['property' => $new_property->getName()], ['action'] );

	} else {
		$message = implode( '', UI_messages::get() );

		$form->setCommonMessage( $message );
	}
}


$snippets['property_add_form_area'] = Application::getView()->render( 'property/create/form' );

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);