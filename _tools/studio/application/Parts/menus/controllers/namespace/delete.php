<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;

$current = Menus::getCurrentMenuNamespace();

if(
	$current
) {

	if( Menus::deleteMenuNamespace( $current->getName() ) ) {
		UI_messages::info( Tr::_('Menu namespace <b>%namespace%</b> has been deleted', [
			'namespace' => $current->getName()
		]) );

		Http_Headers::reload([], ['action', 'namespace']);
	}

}
