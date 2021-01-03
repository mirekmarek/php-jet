<?php

//Debug_Profiler::blockStart('INIT - PHP');

use Jet\SysConf_Path;
use Jet\SysConf_Jet;

error_reporting( E_ALL|E_STRICT );

set_include_path(
	SysConf_Path::LIBRARY().PATH_SEPARATOR.get_include_path()
);

if(SysConf_Jet::getTimezone()) {
	date_default_timezone_set( SysConf_Jet::getTimezone() );
}

ini_set( 'default_charset', SysConf_Jet::getCharset() );

if( function_exists( 'ini_set' ) ) {
	/** @noinspection PhpUsageOfSilenceOperatorInspection */
	@ini_set( 'error_log', SysConf_Path::LOGS().'php_errors_'.@date( 'Y-m-d' ).'.log' );
}
//Debug_Profiler::blockEnd('INIT - PHP');
