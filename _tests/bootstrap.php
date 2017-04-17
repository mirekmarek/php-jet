<?php
if(!ini_get('date.timezone')){
	date_default_timezone_set('UTC');
}

set_include_path(
	getcwd()
		.PATH_SEPARATOR
		.get_include_path()
);

$lib_dir = dirname(__DIR__).'/library/';
define( '_JET_TEST_LIB_DIR', $lib_dir );

spl_autoload_register( function( $class_name ) {

	if(
		substr($class_name, 0, 4)!='Jet\\'
	) {
		return false;
	}

	$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
	$class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );
	/** @noinspection PhpIncludeInspection */
	require _JET_TEST_LIB_DIR.$class_name.'.php';

	return true;
}, true, true );

define('JET_TAB', "\t");
define('JET_EOL', PHP_EOL);

define('JET_TESTS_TMP', getcwd().'/_tmp/');
define('JET_TESTS_DATA', getcwd().'/_data/');
define('JET_LIBRARY_PATH', _JET_TEST_LIB_DIR);

define('JET_OBJECT_REFLECTION_CACHE_LOAD', false );
define('JET_OBJECT_REFLECTION_CACHE_SAVE', false );
define('JET_OBJECT_REFLECTION_CACHE_PATH', JET_TESTS_TMP.'reflections/' );

define('JET_DATAMODEL_DEFINITION_CACHE_LOAD', false );
define('JET_DATAMODEL_DEFINITION_CACHE_SAVE', false );
define('JET_DATAMODEL_DEFINITION_CACHE_PATH', JET_TESTS_TMP.'datamodel_definitions/' );

define('JET_CONFIG_DEFINITION_CACHE_LOAD', false );
define('JET_CONFIG_DEFINITION_CACHE_SAVE', false );
define('JET_CONFIG_DEFINITION_CACHE_PATH', JET_TESTS_TMP.'config_definitions/' );

define('JET_APPLICATION_MODULES_HANDLER_CLASS_NAME', 'Jet\Application_Modules_Handler');
define('JET_APPLICATION_MODULE_MANIFEST_CLASS_NAME', 'Jet\Application_Modules_Module_Manifest');
define('JET_APPLICATION_MODULE_NAMESPACE', 'JetApplicationModule');
define('JET_APPLICATION_MODULES_LIST_PATH', JET_TESTS_TMP.'modules_list.php');


define('JET_IO_CHMOD_MASK_DIR', 0777);
define('JET_IO_CHMOD_MASK_FILE', 0666);

define('JET_CHARSET', 'UTF-8');

require '_mock/Jet/BaseObject.php';
require 'class_names.php';

Jet\Application::setConfigFilePath( __DIR__.'/application-test-config.php' );
Jet\Application::doNotEnd();