<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

require_once SysConf_Path::getLibrary() . 'Jet/SysConf/Jet.php';

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );
SysConf_Jet::setHideHttpRequest( true );


SysConf_Jet::setDevelMode( true );

if( SysConf_Jet::isDevelMode() ) {
	//Dev configuration
	SysConf_Jet::setDebugProfilerEnabled( true );

	SysConf_Jet::setCSSPackagerEnabled( false );
	SysConf_Jet::setJSPackagerEnabled( false );

	SysConf_Jet::setCacheMvcEnabled( false );
	SysConf_Jet::setCacheAutoloaderEnabled( false );

	SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( true );
} else {
	//Production configuration
	SysConf_Jet::setDebugProfilerEnabled( false );

	SysConf_Jet::setCSSPackagerEnabled( true );
	SysConf_Jet::setJSPackagerEnabled( true );

	SysConf_Jet::setCacheMvcEnabled( true );
	SysConf_Jet::setCacheAutoloaderEnabled( true );

	SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( false );

}
