<?php
if(!ini_get('date.timezone')){
	date_default_timezone_set('Europe/Prague');
}

set_include_path(
	getcwd()
		.PATH_SEPARATOR
		.get_include_path()
);

$lib_dir = dirname(__DIR__).'/library/';
define( '_JET_TEST_LIB_DIR', $lib_dir );

spl_autoload_register( function( $class_name ) {
	global $lib_dir;

	if(
		substr($class_name, 0, 4)!='Jet\\'
	) {
		return false;
	}

	$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
	$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
	//var_dump($lib_dir, _JET_TEST_LIB_DIR, $class_name, $lib_dir.$class_name.'.php');

	require _JET_TEST_LIB_DIR.$class_name.'.php';

	return true;
}, true, true );

define('JET_TAB', "\t");
define('JET_EOL', PHP_EOL);

define('JET_TESTS_TMP', getcwd().'/_tmp/');
define('JET_TESTS_DATA', getcwd().'/_data/');

require '_mock/Jet/Object.php';

Jet\Config::setApplicationConfigFilePath( __DIR__.'/application-test-config.php' );