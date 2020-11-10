<?php
namespace JetStudio;

use Jet\UI_messages;
use Jet\Tr;
use Jet\Http_Headers;
use Jet\Http_Request;

$current = Modules::getCurrentModule();

if(
	$current &&
	$current->catchEditForm()
) {
	if( Modules::save() ) {
		UI_messages::success( Tr::_('Saved ...') );

		if(Http_Request::POST()->getBool('generate')) {

			$ok = true;
			try {
				$current->generate();
			} catch( \Exception $e ) {
				$ok = false;
				Application::handleError( $e );
			}

			if( $ok ) {
				UI_messages::success(Tr::_('Generated ...'));
			}

		}


		Http_Headers::reload([], ['action']);
	}

} else {
	UI_messages::danger(
		Tr::_('There are some problems ... Please check the form.')
	);
}
