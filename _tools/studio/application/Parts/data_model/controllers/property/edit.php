<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$class = DataModels::getCurrentClass();
$property = DataModels::getCurrentProperty();

if( !$property ) {
	Application::end();
}


if( $property->catchEditForm() ) {

	if( $property->update( $class ) ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
	}

	Http_Headers::reload( [], ['action'] );
} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
