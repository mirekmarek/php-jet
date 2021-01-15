<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$current = DataModels::getCurrentModel();

$key = $current->getCustomKey( Http_Request::GET()->getString( 'key' ) );

/**
 * @var DataModel_Definition_Key $key
 */
if( !$key ) {
	Application::end();
}

if( $key->catchEditForm() ) {
	if( $current->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
	}
	Http_Headers::reload( [], ['action'] );
} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}
