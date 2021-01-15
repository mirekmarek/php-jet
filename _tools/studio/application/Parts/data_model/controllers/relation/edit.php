<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Tr;
use Jet\UI_messages;

$current = DataModels::getCurrentModel();

$relation = $current->getExternalRelation( Http_Request::GET()->getString( 'relation' ) );

/**
 * @var DataModel_Definition_Relation_External $relation
 */
if( !$relation ) {
	Application::end();
}

if( $relation->catchEditForm() ) {
	if( $current->save() ) {
		UI_messages::success( Tr::_( 'Saved ...' ) );
	}
	Http_Headers::reload( [], ['action'] );
} else {
	UI_messages::danger(
		Tr::_( 'There are some problems ... Please check the form.' )
	);
}



