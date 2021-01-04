<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet;

require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet.php';

SysConf_Jet::setDevelMode( true );
SysConf_Jet::setDebugProfilerEnabled( false );

SysConf_Jet::setCSSPackagerEnabled( false );
SysConf_Jet::setJSPackagerEnabled( false );

SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( true );

SysConf_Jet::setHideHttpRequest( true );

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );

SysConf_Jet::setCacheMvcEnabled( false );
SysConf_Jet::setCacheAutoloaderEnabled( false );
