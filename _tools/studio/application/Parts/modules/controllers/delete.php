<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Modules::getCurrentModule();

if(
	$current
) {
	if(Modules::deleteModule( $current->getInternalId() )) {
		UI_messages::info( Tr::_('Modules <b>%module%</b> has been deleted', [
			'module' => $current->getName()
		]) );

		Http_Headers::reload([], ['action', 'module']);
	}

}
