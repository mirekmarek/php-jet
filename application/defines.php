<?php
/**
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package application bootstrap
 */
namespace Jet;
error_reporting(E_ALL | E_STRICT);


define('JET_APPLICATION_CONFIGURATION_NAME', 'main');
define('JET_DEBUG_MODE', true);
define('JET_DEBUG_PROFILER_ENABLED', JET_DEBUG_MODE);
//define('JET_DEBUG_PROFILER_ENABLED', true);

define('JET_BASE_PATH', dirname(__DIR__).'/');
define('JET_LIBRARY_PATH', JET_BASE_PATH.'library/');

define('JET_APPLICATION_PATH', __DIR__.'/');
define('JET_ERROR_PAGES_PATH', JET_APPLICATION_PATH.'error_pages/');
define('JET_CONFIG_PATH', JET_APPLICATION_PATH.'config/');
define('JET_MODULES_PATH', JET_APPLICATION_PATH.'modules/');
define('JET_SITES_PATH', JET_APPLICATION_PATH.'sites/');
define('JET_DATA_PATH', JET_APPLICATION_PATH.'data/');
define('JET_LOGS_PATH', JET_APPLICATION_PATH.'logs/');
define('JET_TMP_PATH', JET_APPLICATION_PATH.'tmp/');

define('JET_TEMPLATES_PATH', JET_BASE_PATH.'templates/');
define('JET_TEMPLATES_SITES_PATH', JET_TEMPLATES_PATH.'sites/');
define('JET_TEMPLATES_MODULES_PATH', JET_TEMPLATES_PATH.'modules/');

define('JET_PUBLIC_PATH', JET_BASE_PATH.'public/');
define('JET_PUBLIC_IMAGES_PATH', JET_BASE_PATH.'public/images/');
define('JET_PUBLIC_SCRIPTS_PATH', JET_BASE_PATH.'public/scripts/');
define('JET_PUBLIC_STYLES_PATH', JET_BASE_PATH.'public/styles/');
define('JET_PUBLIC_LIBS_PATH', JET_BASE_PATH.'public/libs/');

define('JET_APPLICATION_MODULE_NAMESPACE', 'JetApplicationModule');
define('JET_APPLICATION_MODULES_LIST_PATH', JET_DATA_PATH.'modules_list.php');

define('JET_OBJECT_REFLECTION_CACHE_LOAD', !JET_DEBUG_MODE );
//define('JET_OBJECT_REFLECTION_CACHE_LOAD', true );
define('JET_OBJECT_REFLECTION_CACHE_SAVE', true );
define('JET_OBJECT_REFLECTION_CACHE_PATH', JET_DATA_PATH.'reflections/' );

define('JET_DATAMODEL_DEFINITION_CACHE_LOAD', !JET_DEBUG_MODE );
//define('JET_DATAMODEL_DEFINITION_CACHE_LOAD', true );
define('JET_DATAMODEL_DEFINITION_CACHE_SAVE', true );
define('JET_DATAMODEL_DEFINITION_CACHE_PATH', JET_DATA_PATH.'datamodel_definitions/' );

define('JET_CONFIG_DEFINITION_CACHE_LOAD', !JET_DEBUG_MODE );
//define('JET_CONFIG_DEFINITION_CACHE_LOAD', true );
define('JET_CONFIG_DEFINITION_CACHE_SAVE', true );
define('JET_CONFIG_DEFINITION_CACHE_PATH', JET_DATA_PATH.'config_definitions/' );

define('JET_FACTORY_DEFINITION_CACHE_LOAD', !JET_DEBUG_MODE );
//define('JET_FACTORY_DEFINITION_CACHE_LOAD', true );
define('JET_FACTORY_DEFINITION_CACHE_SAVE', true );
define('JET_FACTORY_DEFINITION_CACHE_PATH', JET_DATA_PATH.'factory_definitions/' );

define('JET_IO_CHMOD_MASK_DIR', 0777);
define('JET_IO_CHMOD_MASK_FILE', 0666);

define('JET_HIDE_HTTP_REQUEST', false);

define('JET_TAB', "\t");
define('JET_EOL', PHP_EOL);

set_include_path(
	JET_LIBRARY_PATH
		.PATH_SEPARATOR
	.get_include_path()
);
