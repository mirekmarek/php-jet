<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Pages::getCurrentPage();

if(
	$current &&
	$current->catchDeleteContentForm()
) {

	if( $current->save() ) {
		UI_messages::info( Tr::_( 'Content has been deleted' ) );

		Http_Headers::reload( [], ['action'] );
	}
}