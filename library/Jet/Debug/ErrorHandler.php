<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
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
	 * @var array|null
	 */
	protected static $last_error;

	/**
	 *
	 */
	public static function initialize()
	{

		if( php_sapi_name()!='cli' ) {
			Debug::enableHTML();
		} else {
			Debug::disableHTML();
		}

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
		if( $code==E_DEPRECATED ) {
			return;
		}

		if(
			$code==E_STRICT &&
			strpos( $file, 'PEAR' )!==false
		) {
			return;
		}

		if(
			$code==E_STRICT &&
			strpos( $message, 'should be compatible with' )
		) {
			return;
		}

		static::$last_error = [
			'type' => $code, 'message' => $message, 'file' => $file, 'line' => $line,
		];

		if( strpos( $file, __NAMESPACE__.'/IO/' )!==false||strpos( $file, __NAMESPACE__.'\\IO\\' )!==false ) {
			return;
		}


		if( error_reporting()==0 ) {
			return;
		}


		$error = Debug_ErrorHandler_Error::newError( $code, $message, $file, $line, $context );
		static::_handleError( $error );
	}

	/**
	 * Exception handler
	 *
	 * @param \Exception $exception
	 */
	public static function handleException( \Exception $exception )
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

	}

	/**
	 * @return array|null
	 */
	public static function getLastError()
	{
		$last_error = static::$last_error;

		static::$last_error = null;

		return $last_error;
	}
}
