<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_ErrorHandler
 */
namespace Jet;

require_once "ErrorHandler/Error.php";
require_once "ErrorHandler/Handler/Abstract.php";


class Debug_ErrorHandler {
	/**
	 * @var null
	 */
	protected static $HTTP_error_pages_dir = null;
	/**
	 * @var bool
	 */
	protected static $HTML_errors_enabled = false;

	/**
	 * @var Debug_ErrorHandler_Handler_Abstract[]
	 */
	protected static $handlers = array();

	/**
	 * @var array|null
	 */
	protected static $last_error;
	
	/**
	 * Registers error and exception handler, setup PHP error_log path and display_errors=off
	 */
	public static function initialize(){

		if( php_sapi_name()!="cli" ) {
			static::enableHTMLErrors();
		} else {
			static::disableHTMLErrors();
		}

		$class_name = get_called_class();

		set_error_handler(array($class_name, "handleError"));
		set_exception_handler(array($class_name, "handleException"));
		register_shutdown_function(array($class_name, "handleShutdown"));

		if( !trim(ini_get("date.timezone")) ){
			date_default_timezone_set("Europe/Prague");
		}
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@ini_set( "error_log", JET_LOGS_PATH . "php_errors_".@date("Y-m-d").".log" );

	}

	/**
	 * @static
	 *
	 */
	public static function enableHTMLErrors() {
		static::$HTML_errors_enabled = true;
	}

	/**
	 * @static
	 *
	 */
	public static function disableHTMLErrors() {
		static::$HTML_errors_enabled = false;
	}

	/**
	 * @static
	 * @return bool
	 */
	public static function getHTMLErrorsEnabled() {
		return static::$HTML_errors_enabled;
	}

	/**
	 * @static
	 *
	 * @param string $handler_name
	 * @param string $handler_class_name
	 * @param string $handler_script_path
	 * @param array $handler_options (optional)
	 *
	 * @return Debug_ErrorHandler_Handler_Abstract
	 */
	public static function registerHandler( $handler_name, $handler_class_name, $handler_script_path, array $handler_options=array() ){
		/** @noinspection PhpIncludeInspection */
		require_once $handler_script_path;

		if(
			!class_exists($handler_class_name, false)
		) {
			die("Error handler: Handler class '{$handler_class_name}' does not exist. Should be in script: '{$handler_script_path}' ");
		}

		$handler = new $handler_class_name( $handler_options );

		if( ! ($handler instanceof Debug_ErrorHandler_Handler_Abstract) ){
			die("Error handler: Handler class '{$handler_class_name}' must extend Debug_ErrorHandler_Handler_Abstract class.");
		}

		static::$handlers[$handler_name] = $handler;

		return $handler;
	}

	/**
	 * @static
	 * @return Debug_ErrorHandler_Handler_Abstract[]
	 */
	public static function getRegisteredHandlers() {
		return static::$handlers;
	}

	/**
	 * @static
	 *
	 * @param $handler_name
	 */
	public static function enableHandler( $handler_name ) {
		static::$handlers[$handler_name]->setIsEnabled(true);
	}

	/**
	 * @static
	 *
	 * @param $handler_name
	 */
	public static function disableHandler( $handler_name ) {
		static::$handlers[$handler_name]->setIsEnabled(false);
	}

	/**
	 * @static
	 *
	 * @param array $handler_names
	 */
	public static function enableHandlers( array $handler_names ) {
		foreach( static::$handlers as $handler_name=>$handler) {
			$handler->setIsEnabled( in_array($handler_name, $handler_names) );
		}
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getDefaultErrorHandlers() {
		return array_keys(static::$handlers);
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getHandlersList() {
		$handlers = array_keys(static::$handlers);
		return array_combine($handlers, $handlers);
	}

	/**
	 * Get path to HTTP error pages directory containing files like 500.phtml , 404.phtml etc. for each HTTP error response code
	 *
	 * @static
	 * @return string
	 */
	public static function getHTTPErrorPagesDir(){
		if(!static::$HTTP_error_pages_dir) {
			static::$HTTP_error_pages_dir = JET_APPLICATION_PATH."error_pages/";
		}

		return static::$HTTP_error_pages_dir;
	}

	/**
	 * Set path to HTTP error pages directory containing files like 500.phtml , 404.phtml etc. for each HTTP error response code
	 *
	 * @static
	 *
	 * @param string $error_pages_dir
	 * @param bool $check_path (optional, default: true)
	 *
	 * @throws Debug_ErrorHandler_Exception
	 */
	public static function setHTTPErrorPagesDir($error_pages_dir, $check_path = true){
		$error_pages_dir = rtrim($error_pages_dir, "/\\".DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		if($check_path && !is_dir($error_pages_dir)){
			throw new Debug_ErrorHandler_Exception(
				"Error pages directory '{$error_pages_dir}' doesn't exist",
				Debug_ErrorHandler_Exception::CODE_INVALID_ERROR_PAGES_DIR_PATH
			);
		}

		static::$HTTP_error_pages_dir = $error_pages_dir;
	}

	/**
	 * Returns HTTP error page file path if exists within Debug_ErrorHandler::$error_pages_dir or false if does not exist or is not readable
	 *
	 * @param $HTTP_code
	 *
	 * @return bool|string
	 */
	public static function getHTTPErrorPageFilePath( $HTTP_code ) {
		$HTTP_code = (int)$HTTP_code;
		$path = static::$HTTP_error_pages_dir.$HTTP_code.".phtml";

		if(is_file($path) && file_exists($path) && is_readable($path)){
			return $path;
		}

		return false;

	}

	/**
	 * Show HTTP error page located in current Debug_ErrorHandler::$error_pages_dir
	 *
	 * Returns FALSE if the file does not exist or is not readable
	 *
	 * @param $HTTP_code
	 *
	 * @return bool
	 */
	public static function displayHTTPErrorPage( $HTTP_code ) {
		$path = static::getHTTPErrorPageFilePath($HTTP_code);
		if(!$path){
			return false;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@ob_end_clean();
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@ob_implicit_flush();

		/** @noinspection PhpIncludeInspection */
		require $path;

		return true;
	}



	/**
	 * PHP error handler - errors are router to registered error handlers instances
	 *
	 * @param int $code
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 * @param array $context
	 */
	public static function handleError($code, $message, $file, $line, $context){
		if( $code==E_DEPRECATED ) {
			return;
		}

		if (
			$code==E_STRICT &&
			strpos($file, "PEAR") !== false
		) {
			return;
		}

		if( $code==E_STRICT && strpos($message, "should be compatible with")) {
			return;
		}

		self::$last_error = array(
			"type" => $code,
			"message" => $message,
			"file" => $file,
			"line" => $line
		);

		if (
			strpos($file, __NAMESPACE__."/IO/") !== false ||
			strpos($file, __NAMESPACE__."\\IO\\") !== false
		) {
			return;
		}


		if( error_reporting()==0 ) {
			return;
		}


		$error = Debug_ErrorHandler_Error::newError( $code, $message, $file, $line, $context );
		static::_handleError($error);
	}

	/**
	 * Exception handler
	 *
	 * @param \Exception $exception
	 */
	public static function handleException(\Exception $exception){
		$error = Debug_ErrorHandler_Error::newException($exception);
		static::_handleError($error);
	}

	/**
	 * @static
	 *
	 * PHP Fatal errors detection
	 */
	public static function handleShutdown(){
		$error = error_get_last();
		if(
			$error &&
			is_array($error)
		){
			$error = Debug_ErrorHandler_Error::newShutdownError($error);
			static::_handleError($error);
		}
	}

	/**
	 * @return array|null
	 */
	public static function getLastError() {
		return self::$last_error;
	}


	/**
	 * @static
	 *
	 * @param Debug_ErrorHandler_Error $error
	 */
	protected static function _handleError( Debug_ErrorHandler_Error $error ) {

		$error_displayed = false;

		if( $error->is_fatal ) {
			if( php_sapi_name()!="cli" ) {
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				@header("HTTP/1.1 500 Internal Server Error");
			}
		}

		foreach( static::$handlers as $handler ) {
			if(!$handler->getIsEnabled()) {
				continue;
			}

			$handler->handle( $error );
			if($handler->errorDisplayed()) {
				$error_displayed = true;
			}
		}

		if( $error->is_fatal ) {
			if( php_sapi_name()!="cli" ) {
				if(!$error_displayed) {
					static::displayHTTPErrorPage(500);
				}
			}

			exit();
		}
	}
}
