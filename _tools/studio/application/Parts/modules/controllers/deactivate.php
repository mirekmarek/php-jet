<?php

namespace JetStudio;

use Jet\Application_Modules;
use Jet\Exception;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if( !$current ) {
	die();
}

if( $current->isInstalled() ) {

	$ok = true;
	try {
		Application_Modules::deactivateModule( $current->getName() );
	} catch( Exception $e ) {
		$ok = false;
		UI_messages::danger( $e->getMessage() );
	}

	if( $ok ) {
		UI_messages::info( Tr::_( 'Module <b>%module%</b> has been deactivated', [
			'module' => $current->getName()
		] ) );
	}

}


Http_Headers::reload( [], ['action'] );
