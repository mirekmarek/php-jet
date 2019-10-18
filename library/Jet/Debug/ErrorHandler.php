<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'ErrorHandler/Error.php';
require_once 'ErrorHandler/Handler.php';


/**
 *
 */
class Debug_ErrorHandler
{

	/**
	 * @var Debug_ErrorHandler_Handler[]
	 */
	protected static $handlers = [];

	/**
	 * @var Debug_ErrorHandler_Error|null
	 */
	protected static $last_error;

	/**
	 * @var array
	 */
	protected static $ignore_non_fatal_errors_paths = [
		'/'.__NAMESPACE__.'/IO/'
	];

	/**
	 * @param string $path
	 */
	public static function addIgnoreNonFatalErrorsPath( $path )
	{
		$path = str_replace('\\', '/', $path);

		static::$ignore_non_fatal_errors_paths[] = $path;
	}

	/**
	 * @return array
	 */
	public static function getIgnoreNonFatalErrorsPaths()
	{
		return self::$ignore_non_fatal_errors_paths;
	}

	/**
	 *
	 */
	public static function initialize()
	{

		Debug::setOutputIsHTML( php_sapi_name()!='cli' );

		$class_name = get_called_class();

		set_error_handler( [ $class_name, 'handleError' ] );
		set_exception_handler( [ $class_name, 'handleException' ] );
		register_shutdown_function( [ $class_name, 'handleShutdown' ] );

	}


	/**
	 * @param Debug_ErrorHandler_Handler $handler
	 *
	 * @return Debug_ErrorHandler_Handler
	 */
	public static function registerHandler( Debug_ErrorHandler_Handler $handler )
	{

		static::$handlers[$handler->getName()] = $handler;

		return $handler;
	}

	/**
	 * @param string $name
	 */
	public static function unRegisterHandler( $name )
	{
		if(isset(static::$handlers[$name])) {
			unset( static::$handlers[$name] );
		}
	}

	/**
	 * @param string $name
	 *
	 * @return Debug_ErrorHandler_Handler|null
	 */
	public static function getHandler( $name )
	{
		if(isset(static::$handlers[$name])) {
			return static::$handlers[$name];
		}

		return null;
	}

	/**
	 *
	 * @return Debug_ErrorHandler_Handler[]
	 */
	public static function getRegisteredHandlers()
	{
		return static::$handlers;
	}

	/**
	 *
	 * @param int    $code
	 * @param string $message
	 * @param string $file
	 * @param int    $line
	 * @param array  $context
	 */
	public static function handleError( $code, $message, $file, $line, $context )
	{
		$error = Debug_ErrorHandler_Error::newError( $code, $message, $file, $line, $context );

		static::$last_error = $error;

		if(!$error->isFatal()) {
			if( error_reporting()==0 ) {
				return;
			}

			foreach( static::$ignore_non_fatal_errors_paths as $path_part ) {
				$win_path_part = str_replace('/', '\\', $path_part);

				if(
					strpos( $file, $path_part )!==false ||
					strpos( $file, $win_path_part )!==false
				) {
					return;
				}
			}
		}

		static::_handleError( $error );
	}

	/**
	 * Exception handler
	 *
	 * @param \Exception|\Error $exception
	 */
	public static function handleException( $exception )
	{
		$error = Debug_ErrorHandler_Error::newException( $exception );
		static::_handleError( $error );
	}

	/**
	 *
	 * PHP Fatal errors detection
	 */
	public static function handleShutdown()
	{
		$error = error_get_last();
		if(
			$error &&
			is_array( $error )
		) {
			$error = Debug_ErrorHandler_Error::newShutdownError( $error );
			static::_handleError( $error );
		}
	}


	/**
	 *
	 * @param Debug_ErrorHandler_Error $error
	 */
	protected static function _handleError( Debug_ErrorHandler_Error $error )
	{
		if(
			substr($error->getMessage(),0, 23)=='POST Content-Length of ' ||
			$error->getMessage()=='Maximum number of allowable file uploads has been exceeded'
		) {
			return;
		}


		$error_displayed = false;

		if( $error->isFatal() ) {
			if( php_sapi_name()!='cli' ) {
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				@header( 'HTTP/1.1 500 Internal Server Error' );
			}
		}

		foreach( static::$handlers as $handler ) {

			$handler->handle( $error );
			if( $handler->errorDisplayed() ) {
				$error_displayed = true;
			}
		}

		if(!$error_displayed) {
			if(Debug::getOutputIsHTML()){
				echo '<pre>'.$error.'</pre>';

			} else {
				echo $error;
			}
		}

		if( $error->isFatal() ) {
			die();
		}
	}

	/**
	 * @return Debug_ErrorHandler_Error|null
	 */
	public static function getLastError()
	{
		$last_error = static::$last_error;

		static::$last_error = null;

		return $last_error;
	}
}
