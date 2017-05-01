<?php
define('JET_BASE_PATH', dirname(dirname(dirname(__DIR__))).'/');

define('JET_TRANSLATOR_DICTIONARIES_BASE_PATH_PATH', JET_BASE_PATH.'dictionaries/');

define('JET_LIBRARY_PATH', JET_BASE_PATH.'library/');
define('JET_SITES_PATH', JET_BASE_PATH.'sites/');
define('JET_PUBLIC_PATH', JET_BASE_PATH.'public/');

define('JET_APPLICATION_PATH', JET_BASE_PATH.'application/');
define('JET_CONFIG_PATH', JET_APPLICATION_PATH.'config/');
define('JET_MODULES_PATH', JET_APPLICATION_PATH.'modules/');
define('JET_DATA_PATH', JET_APPLICATION_PATH.'data/');
define('JET_LOGS_PATH', JET_APPLICATION_PATH.'logs/');
define('JET_TMP_PATH', JET_APPLICATION_PATH.'tmp/');

define('JET_APPLICATION_MODULES_LIST_PATH', JET_DATA_PATH.'modules_list.php');

define('JET_OBJECT_REFLECTION_CACHE_PATH', JET_DATA_PATH.'reflections/' );
define('JET_DATAMODEL_DEFINITION_CACHE_PATH', JET_DATA_PATH.'datamodel_definitions/' );
define('JET_CONFIG_DEFINITION_CACHE_PATH', JET_DATA_PATH.'config_definitions/' );
define('JET_AUTOLOADER_CACHE_PATH', JET_DATA_PATH);

define('JETAPP_EMAIL_TEMPLATES_PATH', JET_APPLICATION_PATH.'email_templates/');