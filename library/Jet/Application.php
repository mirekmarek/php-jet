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
	 * @var string
	 */
	protected static $environment;

	/**
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 * Start application
	 *
	 * @static
	 *
	 * @param string|null $environment (optional, default = JET_APPLICATION_ENVIRONMENT constant)
	 * @param string|null $config_file_path (optional,default = JET_APPLICATION_PATH."/configs/".$environment.".php")
	 *
	 * @throws Application_Exception
	 */
	public static function start( $environment = null, $config_file_path = null ){
		if(!$environment){
			if(!defined("JET_APPLICATION_ENVIRONMENT")){
				throw new Application_Exception(
					"Constant JET_APPLICATION_ENVIRONMENT is not defined.",
					Application_Exception::CODE_ENVIRONMENT_NOT_SET
				);
			}

			$environment = JET_APPLICATION_ENVIRONMENT;
		}

		if(!preg_match("/^([a-zA-Z0-9_\-]{1,})$/", $environment)){
			throw new Application_Exception(
				"Invalid environment name '{$environment}'. Valid name is: '/^([a-zA-Z0-9_\-]{1,})$/' ",
				Application_Exception::CODE_INVALID_ENVIRONMENT_NAME

			);
		}

		static::$environment = $environment;

		if(!$config_file_path){
			$config_file_path = JET_APPLICATION_CONFIG_PATH . "{$environment}.php";
		}

		Config::setApplicationConfigFilePath( $config_file_path );

		static::setupErrorHandler();
		Http_Request::initialize( Application::getConfig()->getHidePHPRequestData() );
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
		exit();
	}

	/**
	 * @return string
	 */
	public function getEnvironment() {
		return static::$environment;
	}

	/**
	 * @static
	 *
	 * @return bool
	 */
	public static function getIsDebugMode(){
		return defined("JET_DEBUG_MODE") && JET_DEBUG_MODE;
	}

	/**
	 * @static
	 *
	 * @return Application_Config
	 */
	public static function getConfig() {
		if(!static::$config) {
			static::$config = new Application_Config();
		}

		return static::$config;
	}

}