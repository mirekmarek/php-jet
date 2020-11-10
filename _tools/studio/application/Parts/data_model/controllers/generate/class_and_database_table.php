<?php
namespace JetStudio;

use Jet\DataModel_Backend;
use Jet\Http_Headers;
use Jet\IO_File;
use Jet\UI_messages;
use Jet\Tr;

$current = DataModels::getCurrentModel();
if(!$current) {
	die();
}

$ok = true;
$updated = false;
try {
	$class = $current->createClass();

	if(!$class) {
		UI_messages::danger( Tr::_('DataModel is not ready! Please check definition errors.') );

		Http_Headers::reload([],['action']);
	}

	$path = $current->getClassPath();

	$updated = !IO_File::exists( $path );

	$class->write( $path );

} catch( \Exception $e ) {
	$ok = false;

	Application::handleError( $e );
}

if($ok) {
	if( $updated ) {
		UI_messages::success( Tr::_( 'Class <b>%class%</b> has been created', ['class'=>$current->getClassName()] ) );
	} else {
		UI_messages::success( Tr::_( 'Class <b>%class%</b> has been updated', ['class'=>$current->getClassName()] ) );
	}

	$current->prepare();

	$backend = DataModel_Backend::get( $current );

	$updated = false;
	$ok = true;

	try {
		if($backend->helper_tableExists( $current )) {
			//echo implode(PHP_EOL.PHP_EOL, $backend->helper_getUpdateCommand( $current ));
			$backend->helper_update( $current );
			$updated = true;
		} else {
			//echo $backend->helper_getCreateCommand( $current );
			$backend->helper_create( $current );
		}
	} catch( \Exception $e ) {
		$ok = false;

		Application::handleError( $e );
	}

	if($ok) {
		if( $updated ) {
			UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been updated', ['table'=>$current->getDatabaseTableName()] ) );
		} else {
			UI_messages::success( Tr::_( 'Database table <b>%table%</b> has been created', ['table'=>$current->getDatabaseTableName()] ) );
		}
	}

}





Http_Headers::reload([],['action']);