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
	Application_Modules::uninstallModule( $current->getName() );
} catch( Exception $e ) {
	$ok = false;
	UI_messages::danger( $e->getMessage() );
}
SysConf_Path::setDictionaries($tr_dir);

if( $ok ) {
	UI_messages::info( Tr::_( 'Module <b>%module%</b> has been uninstalled', [
		'module' => $current->getName()
	] ) );
}

Http_Headers::reload( [], ['action'] );
