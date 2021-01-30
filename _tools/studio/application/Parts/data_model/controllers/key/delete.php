<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

/**
 * @var DataModel_Definition_Key $key
 */
if(
	$current &&
	($key = $current->getCustomKey( Http_Request::GET()->getString( 'key' ) ))
) {
	$current->deleteCustomKey( $key->getName() );

	if( $current->save() ) {
		UI_messages::info(
			Tr::_( 'Key <strong>%key%</strong> has been deleted', ['key' => $key->getName()] )
		);

	}
}

Http_Headers::reload( [], [
	'action',
	'key'
] );

