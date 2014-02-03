<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 */
namespace Jet;

class Application {

	/**
	 * @var bool
	 */
	protected static $do_not_end = false;

	/**
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 *
	 */
	public static function installCommonDictionaries() {
		$dictionaries_path = JET_APPLICATION_PATH."_install/dictionaries/";

		$list = IO_Dir::getList( $dictionaries_path, '*.php' );

		$tr_backend_type = 'PHPFiles';

		$backend = Translator_Factory::getBackendInstance( $tr_backend_type );

		foreach( $list as $path=>$file_name ) {
			list($locale) = explode('.', $file_name);
			$locale = new Locale($locale);

			$dictionary = $backend->loadDictionary( Tr::COMMON_NAMESPACE, $locale, $path );

			$backend->saveDictionary( $dictionary );
		}
	}

	/**
	 * Start application
	 *
	 * @static
	 *
	 * @param string $configuration_name
	 *
	 * @throws Application_Exception
	 */
	public static function start( $configuration_name ){

		Debug_Profiler::MainBlockStart('Application init');

		if( strpos($configuration_name, '.')!==false ) {
			throw new Application_Exception(
				'Invalid configuration name \''.$configuration_name.'\'',
				Application_Exception::CODE_INVALID_CONFIGURATION_NAME

			);
		}

		$config_file_path = JET_CONFIG_PATH . $configuration_name.'.php';

		Debug_Profiler::blockStart('Configuration init');
			Config::setApplicationConfigFilePath( $config_file_path );
			static::getConfig();
		Debug_Profiler::blockEnd('Configuration init');

		Debug_Profiler::blockStart('Error handler init');
			static::setupErrorHandler();
		Debug_Profiler::blockEnd('Error handler init');

		Debug_Profiler::blockStart('Http request init');
			Http_Request::initialize( Application::getConfig()->getHidePHPRequestData() );
		Debug_Profiler::blockEnd('Http request init');

		Debug_Profiler::MainBlockEnd('Application init');
	}

	/**
	 * @return Application_Config|null
	 */
	public static function getConfig() {
		if(!static::$config) {
			static::$config = new Application_Config();
		}

		return static::$config;
	}

	/**
	 * Setup error handlers
	 *
	 * @static
	 *
	 */
	public static function setupErrorHandler() {
		$enable_handlers = static::getConfig()->getErrorHandlers();
		$registered_handlers = Debug_ErrorHandler::getRegisteredHandlers();

		foreach($registered_handlers as $handler_name=>$handler ) {
			if(!isset($enable_handlers[$handler_name])) {
				$handler->setIsEnabled( false );
				continue;
			}
			$handler->setIsEnabled( true );
			$handler->setOptions($enable_handlers[$handler_name]);
		}
	}

	/**
	 * @static
	 *
	 */
	public static function end(){
		if(!static::$do_not_end) {
			exit();
		}
	}

	/**
	 * Useful for tests
	 *
	 */
	public static function doNotEnd() {
		static::$do_not_end = true;
	}

	/**
	 * @static
	 *
	 * @return bool
	 */
	public static function getIsDebugMode(){
		return defined('JET_DEBUG_MODE') && JET_DEBUG_MODE;
	}

}