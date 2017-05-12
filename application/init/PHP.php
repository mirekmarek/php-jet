<?php
error_reporting( E_ALL|E_STRICT );

set_include_path(
	JET_LIBRARY_PATH.PATH_SEPARATOR.get_include_path()
);

date_default_timezone_set( JET_TIMEZONE );

ini_set( 'default_charset', JET_CHARSET );

if( function_exists( 'ini_set' ) ) {
	/** @noinspection PhpUsageOfSilenceOperatorInspection */
	@ini_set( 'error_log', JET_LOGS_PATH.'php_errors_'.@date( 'Y-m-d' ).'.log' );
}
