<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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

		set_error_handler( [
			static::class,
			'handleError'
		] );
		set_exception_handler( [
			static::class,
			'handleException'
		] );

	}


	/**
	 * @param Debug_ErrorHandler_Handler $handler
	 */
	public static function registerHandler( Debug_ErrorHandler_Handler $handler ): void
	{

		static::$handlers[$handler->getName()] = $handler;
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
	 */
	public static function handleError( int $code, string $message, string $file = '', int $line = 0 ): void
	{
		$error = Debug_ErrorHandler_Error::newError( $code, $message, $file, $line );

		if( !$error->isFatal() ) {

			foreach( static::$ignore_non_fatal_errors_paths as $path_part ) {
				$win_path_part = str_replace( '/', '\\', $path_part );

				if(
					str_contains( $file, $path_part ) ||
					str_contains( $file, $win_path_part )
				) {
					$error->setIsSilenced( true );
					break;
				}
			}

			$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

			foreach($backtrace as $bt) {
				if(
					($bt['class']??'')==static::class &&
					($bt['function']??'')=='doItSilent'
				) {
					$error->setIsSilenced(true);
					break;
				}
			}
		}

		static::$last_error = $error;

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
	 * @param Debug_ErrorHandler_Error $error
	 */
	protected static function _handleError( Debug_ErrorHandler_Error $error ): void
	{
		if(
			str_starts_with( $error->getMessage(), 'POST Content-Length of ' ) ||
			$error->getMessage() == 'Maximum number of allowable file uploads has been exceeded'
		) {
			return;
		}


		$error_displayed = false;

		foreach( static::$handlers as $handler ) {
			$handler->handle( $error );
			if( $handler->errorDisplayed() ) {
				$error_displayed = true;
			}
		}

		if(
			!$error_displayed &&
			$error->isFatal() &&
			SysConf_Jet_Debug::getDevelMode()
		) {
			if( Debug::getOutputIsHTML() ) {
				echo '<pre>' . $error . '</pre>';

			} else {
				echo $error;
			}

			die();
		}
	}

	/**
	 *
	 */
	public static function resetLastError() : void
	{
		static::$last_error = null;
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

	/**
	 * @param callable $operation
	 *
	 * @return mixed
	 */
	public static function doItSilent( callable $operation ) : mixed
	{
		return $operation();
	}
}
