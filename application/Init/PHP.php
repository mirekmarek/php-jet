<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

//Debug_Profiler::blockStart('INIT - PHP');

use Jet\SysConf_Jet;
use Jet\SysConf_Path;

error_reporting( E_ALL | E_STRICT );

set_include_path(
	SysConf_Path::getLibrary() . PATH_SEPARATOR . get_include_path()
);

if( SysConf_Jet::getTimezone() ) {
	date_default_timezone_set( SysConf_Jet::getTimezone() );
}

if( function_exists( 'ini_set' ) ) {
	ini_set( 'default_charset', SysConf_Jet::getCharset() );

	/** @noinspection PhpUsageOfSilenceOperatorInspection */
	@ini_set( 'error_log', SysConf_Path::getLogs() . 'php_errors_' . @date( 'Y-m-d' ) . '.log' );
}
//Debug_Profiler::blockEnd('INIT - PHP');
