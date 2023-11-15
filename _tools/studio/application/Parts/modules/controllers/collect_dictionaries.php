<?php

namespace JetStudio;

use Jet\Exception;
use Jet\Http_Headers;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;

$current = Modules::getCurrentModule();

if( !$current ) {
	die();
}



if( $current->isInstalled() ) {
	
	$ok = true;
	$tr_dir = SysConf_Path::getDictionaries();
	
	try {
		
		SysConf_Path::setDictionaries(ProjectConf_Path::getDictionaries());
		Translator::collectApplicationModuleDictionaries( $current );
		
	} catch( Exception $e ) {
		$ok = false;
		UI_messages::danger( $e->getMessage() );
	}
	SysConf_Path::setDictionaries($tr_dir);
	
	if( $ok ) {
		UI_messages::success( Tr::_( 'Module dictionaries has been collected', [
			'module' => $current->getName()
		] ) );
	}
	
}


Http_Headers::reload( [], ['action'] );
