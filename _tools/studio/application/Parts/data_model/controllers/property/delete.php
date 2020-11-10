<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

/**
 * @var DataModels_Property_Interface $property
 */
if(
	$current &&
	($property = $current->getProperty( Http_Request::GET()->getString('property') )) &&
	$property->canBeDeleted()
) {
	$current->deleteProperty( $property->getInternalId() );

	if(DataModels::save()) {
		UI_messages::info(
			Tr::_('Property <strong>%property%</strong> has been deleted', ['property'=>$property->getName()])
		);

		Http_Headers::reload([], ['action','property']);
	}
}

