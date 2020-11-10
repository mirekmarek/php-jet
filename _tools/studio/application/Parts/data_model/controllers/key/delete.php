<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

/**
 * @var DataModels_Key $key
 */
if(
	$current &&
	($key = $current->getKey( Http_Request::GET()->getString('key') ))
) {
	$current->deleteKey( $key->getInternalId() );

	if(DataModels::save()) {
		UI_messages::info(
			Tr::_('Key <strong>%key%</strong> has been deleted', ['key'=>$key->getName()])
		);

		Http_Headers::reload([], ['action','key']);
	}

}

