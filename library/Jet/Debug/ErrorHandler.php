<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Throwable;

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
	protected static array $handlers = [];

	/**
	 * @var Debug_ErrorHandler_Error|null
	 */
	protected static Debug_ErrorHandler_Error|null $last_error = null;

	/**
	 * @var array
	 */
	protected static array $ignore_non_fatal_errors_paths = [
		'/' . __NAMESPACE__ . '/IO/'
	];

	/**
	 * @param string $path
	 */
	public static function addIgnoreNonFatalErrorsPath( string $path ): void
	{
		$path = str_replace( '\\', '/', $path );

		static::$ignore_non_fatal_errors_paths[] = $path;
	}

	/**
	 * @return array
	 */
	public static function getIgnoreNonFatalErrorsPaths(): array
	{
		return self::$ignore_non_fatal_errors_paths;
	}

	/**
	 *
	 */
	public static function initialize(): void
	{

		Debug::setOutputIsHTML( php_sapi_name() != 'cli' );

		$class_name = get_called_class();

		set_error_handler( [
			$class_name,
			'handleError'
		] );
		set_exception_handler( [
			$class_name,
			'handleException'
		] );
		register_shutdown_function( [
			$class_name,
			'handleShutdown'
		] );

	}


	/**
	 * @param Debug_ErrorHandler_Handler $handler
	 *
	 * @return Debug_ErrorHandler_Handler
	 */
	public static function registerHandler( Debug_ErrorHandler_Handler $handler ): Debug_ErrorHandler_Handler
	{

		static::$handlers[$handler->getName()] = $handler;

		return $handler;
	}

	/**
	 * @param string $name
	 */
	public static function unRegisterHandler( string $name ): void
	{
		if( isset( static::$handlers[$name] ) ) {
			unset( static::$handlers[$name] );
		}
	}

	/**
	 * @param string $name
	 *
	 * @return Debug_ErrorHandler_Handler|null
	 */
	public static function getHandler( string $name ): Debug_ErrorHandler_Handler|null
	{
		if( isset( static::$handlers[$name] ) ) {
			return static::$handlers[$name];
		}

		return null;
	}

	/**
	 *
	 * @return Debug_ErrorHandler_Handler[]
	 */
	public static function getRegisteredHandlers(): array
	{
		return static::$handlers;
	}

	/**
	 *
	 * @param int $code
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 * @param array $context
	 */
	public static function handleError( int $code, string $message, string $file = '', int $line = 0, array $context = [] ): void
	{
		if( str_contains( $message, 'should not be abstract' ) ) {
			return;
		}

		$error = Debug_ErrorHandler_Error::newError( $code, $message, $file, $line, $context );

		static::$last_error = $error;

		if( !$error->isFatal() ) {
			if( error_reporting() == 0 ) {
				return;
			}

			foreach( static::$ignore_non_fatal_errors_paths as $path_part ) {
				$win_path_part = str_replace( '/', '\\', $path_part );

				if(
					str_contains( $file, $path_part ) ||
					str_contains( $file, $win_path_part )
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
	 * @param Throwable $exception
	 */
	public static function handleException( Throwable $exception ): void
	{
		$error = Debug_ErrorHandler_Error::newException( $exception );
		static::_handleError( $error );
	}

	/**
	 *
	 * PHP Fatal errors detection
	 */
	public static function handleShutdown(): void
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
	protected static function _handleError( Debug_ErrorHandler_Error $error ): void
	{
		if(
			substr( $error->getMessage(), 0, 23 ) == 'POST Content-Length of ' ||
			$error->getMessage() == 'Maximum number of allowable file uploads has been exceeded'
		) {
			return;
		}


		$error_displayed = false;

		if( $error->isFatal() ) {
			if( php_sapi_name() != 'cli' ) {
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

		if( !$error_displayed ) {
			if( Debug::getOutputIsHTML() ) {
				echo '<pre>' . $error . '</pre>';

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
	public static function getLastError(): Debug_ErrorHandler_Error|null
	{
		$last_error = static::$last_error;

		static::$last_error = null;

		return $last_error;
	}
}
