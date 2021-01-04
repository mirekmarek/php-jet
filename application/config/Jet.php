<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet.php';

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );
SysConf_Jet::setHideHttpRequest( true );


//Dev configuration:
SysConf_Jet::setDevelMode( true );
SysConf_Jet::setDebugProfilerEnabled( true );

SysConf_Jet::setCSSPackagerEnabled( false );
SysConf_Jet::setJSPackagerEnabled( false );

SysConf_Jet::setCacheMvcEnabled( false );
SysConf_Jet::setCacheAutoloaderEnabled( false );

SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( true );


//Production configuration:
/*
SysConf_Jet::setDevelMode( false );
SysConf_Jet::setDebugProfilerEnabled( false );

SysConf_Jet::setCSSPackagerEnabled( true );
SysConf_Jet::setJSPackagerEnabled( true );

SysConf_Jet::setCacheMvcEnabled(true);
SysConf_Jet::setCacheAutoloaderEnabled(true);

SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( false );
*/

