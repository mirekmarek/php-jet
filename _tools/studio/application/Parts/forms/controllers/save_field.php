<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$property = Forms::getCurrentProperty();

$form = $property->getDefinitionForm();
if(!$form) {
	return;
}

if($form->catchInput() && $form->validate()) {
	if($property->update( $form->getValues() )) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
		
		Http_Headers::reload(unset_GET_params: ['action']);
	}
} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
	
}