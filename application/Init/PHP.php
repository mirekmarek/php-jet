<?php

//Debug_Profiler::blockStart('INIT - PHP');

use Jet\SysConf_PATH;

error_reporting( E_ALL|E_STRICT );

set_include_path(
	SysConf_PATH::LIBRARY().PATH_SEPARATOR.get_include_path()
);

date_default_timezone_set( JET_TIMEZONE );


if( function_exists( 'ini_set' ) ) {
	ini_set( 'default_charset', JET_CHARSET );

	/** @noinspection PhpUsageOfSilenceOperatorInspection */
	@ini_set( 'error_log', SysConf_PATH::LOGS().'php_errors_'.@date( 'Y-m-d' ).'.log' );
}
//Debug_Profiler::blockEnd('INIT - PHP');
