<?php

namespace JetStudio;

use Jet\AJAX;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentClass();

$ok = false;
$data = [];
$snippets = ['add_property_form_message'=>''];


if( ($new_properties = DataModel_Definition_Property::catchCreateForm( $current )) ) {

	$ok = true;
	foreach($new_properties as $new_property) {

		if( $new_property->add( $current ) ) {
			UI_messages::success(
				Tr::_( 'Property <strong>%property%</strong> has been created', [
					'property' => $new_property->getName()
				] )
			);
		} else {

			die('Error ???');
			$ok = false;
			
			$snippets['add_property_form_message'] .= implode( '', UI_messages::get() );
		}
	}
}

AJAX::operationResponse(
	$ok,
	$snippets,
	$data
);