<?php
namespace JetStudio;

use Jet\Http_Headers;
use Jet\Exception;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();

if(
	$current &&
	$current->catchEditForm()
) {

	if(DataModels::save()) {
		UI_messages::success( Tr::_('Saved ...') );

		Http_Headers::reload([], ['action']);
	}


}
