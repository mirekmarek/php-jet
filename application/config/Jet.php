<?php
use Jet\SysConf_PATH;
use Jet\SysConf_Jet;

require_once SysConf_PATH::LIBRARY().'Jet/SysConf/Jet.php';

//Dev configuration:
SysConf_Jet::setDEVEL_MODE( true );
SysConf_Jet::setDEBUG_PROFILER_ENABLED( true );

SysConf_Jet::setLAYOUT_CSS_PACKAGER_ENABLED( false );
SysConf_Jet::setLAYOUT_JS_PACKAGER_ENABLED( false );

SysConf_Jet::setTRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE( true );

SysConf_Jet::setCACHE_LOAD( false );
SysConf_Jet::setCACHE_SAVE( false );

SysConf_Jet::setHIDE_HTTP_REQUEST( true );

SysConf_Jet::setTIMEZONE( 'Europe/Prague' );


//Production configuration:
/*
SysConf_Jet::setDEVEL_MODE( false );
SysConf_Jet::setDEBUG_PROFILER_ENABLED( false );

SysConf_Jet::setLAYOUT_CSS_PACKAGER_ENABLED( true );
SysConf_Jet::setLAYOUT_JS_PACKAGER_ENABLED( true );

SysConf_Jet::setTRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE( false );

SysConf_Jet::setCACHE_LOAD( true );
SysConf_Jet::setCACHE_SAVE( true );

SysConf_Jet::setHIDE_HTTP_REQUEST( true );

SysConf_Jet::setTIMEZONE( 'Europe/Prague' );
*/