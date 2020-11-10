<?php
namespace JetStudio;

use Jet\Exception;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = Sites::getCurrentSite();

if(
	$current &&
	Sites::catchEditForm()
) {

	$ok = true;
	try {
		$current->save();
	} catch( Exception $e ) {
		$ok = false;

		Application::handleError( $e );
	}

	if( $ok ) {
		UI_messages::success(Tr::_('Saved ...'));
	}



	Http_Headers::reload([], ['action']);

} else {
		UI_messages::danger(
			Tr::_('There are some problems ... Please check the form.')
		);
}
