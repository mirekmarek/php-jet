<?php

namespace JetStudio;

use Jet\Application_Modules;
use Jet\Exception;
use Jet\Http_Headers;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if( !$current ) {
	die();
}

$tr_dir = SysConf_Path::getDictionaries();
$ok = true;
try {
	SysConf_Path::setDictionaries(ProjectConf_Path::getDictionaries());
	Application_Modules::installModule( $current->getName() );
	Application_Modules::activateModule( $current->getName() );
} catch( Exception $e ) {
	$ok = false;
	UI_messages::danger( $e->getMessage() );
}
SysConf_Path::setDictionaries($tr_dir);

if( $ok ) {
	UI_messages::success( Tr::_( 'Module <b>%module%</b> has been installed and activated', [
		'module' => $current->getName()
	] ) );
}

Http_Headers::reload( [], ['action'] );
