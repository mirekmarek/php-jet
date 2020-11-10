<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

/**
 * @var DataModels_OuterRelation $relation
 */
if(
	$current &&
	($relation = $current->getOuterRelation( Http_Request::GET()->getString('relation') ))
) {
	$current->deleteOuterRelation( $relation->getInternalId() );

	if(DataModels::save()) {
		UI_messages::info(
			Tr::_('Relation <strong>%relation%</strong> has been deleted', ['relation'=>$relation->getName()])
		);

		Http_Headers::reload([], ['action','key']);
	}

}

