<?php

namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

$relation_name = Http_Request::GET()->getString( 'relation' );

/**
 * @var DataModel_Definition_Relation_External $relation
 */
if(
	$current &&
	($relation = $current->getExternalRelation( $relation_name ))
) {
	$current->deleteExternalRelation( $relation_name );

	if( $current->save() ) {
		UI_messages::info(
			Tr::_( 'Relation <strong>%relation%</strong> has been deleted', ['relation' => $relation->getName()] )
		);

	}
}

Http_Headers::reload( [], [
	'action',
	'relation'
] );


