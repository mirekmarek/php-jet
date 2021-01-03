<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet;
use Jet\SysConf_Cache;

require_once SysConf_Path::LIBRARY().'Jet/SysConf/Jet.php';
require_once SysConf_Path::LIBRARY().'Jet/SysConf/Cache.php';

SysConf_Jet::setDevelMode( true );
SysConf_Jet::setDebugProfilerEnabled( false );

SysConf_Jet::setCSSPackagerEnabled( false );
SysConf_Jet::setJSPackagerEnabled( false );

SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( true );

SysConf_Jet::setHideHttpRequest( true );

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );

SysConf_Cache::setMvcEnabled( false );
SysConf_Cache::setAutoloaderEnabled( false );
