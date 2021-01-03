<?php
use Jet\SysConf_Path;
use Jet\SysConf_Jet;
use Jet\SysConf_Cache;

require_once SysConf_Path::getLibrary().'Jet/SysConf/Jet.php';
require_once SysConf_Path::getLibrary().'Jet/SysConf/Cache.php';

//Dev configuration:
SysConf_Jet::setDevelMode( true );
SysConf_Jet::setDebugProfilerEnabled( true );

SysConf_Jet::setCSSPackagerEnabled( true );
SysConf_Jet::setJSPackagerEnabled( true );

SysConf_Jet::setTranslatorAutoAppendUnknownPhrase( true );

SysConf_Jet::setHideHttpRequest( true );

SysConf_Cache::setMvcEnabled(true);
SysConf_Cache::setAutoloaderEnabled(true);

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );


//Production configuration:
/*
SysConf_Jet::setDEVEL_MODE( false );
SysConf_Jet::setDEBUG_PROFILER_ENABLED( false );

SysConf_Jet::setLAYOUT_CSS_PACKAGER_ENABLED( true );
SysConf_Jet::setLAYOUT_JS_PACKAGER_ENABLED( true );

SysConf_Jet::setTRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE( false );

SysConf_Jet::setHIDE_HTTP_REQUEST( true );

SysConf_Cache::setMVC_ENABLED(true);
SysConf_Cache::setAUTOLOADER_ENABLED(true);

//SysConf_Jet::setTIMEZONE( 'Europe/Prague' );
*/