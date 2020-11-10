<?php
namespace JetStudio;

use Jet\Http_Request;
use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Modules::getCurrentModule();

$GET = Http_Request::GET();

if(
	$current &&
	($controller_id=$GET->getString('controller')) &&
	( $old_controller=$current->deleteController( $controller_id ) )
) {

	if( Modules::save() ) {
		UI_messages::info( Tr::_('Controller <b>%controller%</b> has been deleted', [
			'controller' => $old_controller->getName()
		]) );

		Http_Headers::reload([], ['action']);
	}

}
