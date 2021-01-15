<?php

namespace JetStudio;

use Exception;
use Jet\DataModel_Backend;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();
if( !$current ) {
	die();
}

$current->prepare();

$backend = DataModel_Backend::get( $current );

$updated = false;
$ok = true;
try {
	if( $backend->helper_tableExists( $current ) ) {
		//echo implode(PHP_EOL.PHP_EOL, $backend->helper_getUpdateCommand( $current ));
		$backend->helper_update( $current );
		$updated = true;
	} else {
		//echo $backend->helper_getCreateCommand( $current );
		$backend->helper_create( $current );
	}
} catch( Exception $e ) {
	$ok = false;

	Application::handleError( $e );
}

if( $ok ) {
	if( $updated ) {
		UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been updated', ['table' => $current->getDatabaseTableName()] ) );
	} else {
		UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been created', ['table' => $current->getDatabaseTableName()] ) );
	}
}

Http_Headers::reload( [], ['action'] );