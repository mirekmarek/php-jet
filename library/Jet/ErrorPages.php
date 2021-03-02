<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class ErrorPages extends BaseObject
{
	/**
	 * @var string
	 */
	protected static string $error_pages_dir = '';

	/**
	 * @var callable[]
	 */
	protected static array $handlers = [];

	/**
	 * @var callable[]
	 */
	protected static array $displayers = [];

	/**
	 *
	 *
	 * @param string $error_pages_dir
	 * @param bool $check_path (optional, default: true)
	 *
	 * @throws ErrorPages_Exception
	 */
	public static function setErrorPagesDir( string $error_pages_dir, bool $check_path = true ): void
	{
		$error_pages_dir = rtrim( $error_pages_dir, '/\\' . DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;

		if(
			$check_path &&
			!is_dir( $error_pages_dir )
		) {
			throw new ErrorPages_Exception(
				'Error pages directory \'' . $error_pages_dir . '\' does not exist',
				ErrorPages_Exception::CODE_INVALID_ERROR_PAGES_DIR_PATH
			);
		}

		static::$error_pages_dir = $error_pages_dir;
	}

	/**
	 *
	 * @return string
	 */
	public static function getErrorPagesDir(): string
	{
		return static::$error_pages_dir;
	}

	/**
	 * @param int $code
	 * @param callable $callback
	 */
	public static function setHandler( int $code, callable $callback ): void
	{
		static::$handlers[$code] = $callback;

	}

	/**
	 * @param int $code
	 */
	public static function unsetHandler( int $code ): void
	{
		if( isset( static::$handlers[$code] ) ) {
			unset( static::$handlers[$code] );
		}
	}


	/**
	 * @param int $code
	 * @param callable $callback
	 */
	public static function setDisplayer( int $code, callable $callback ): void
	{
		static::$displayers[$code] = $callback;

	}

	/**
	 * @param int $code
	 */
	public static function unsetDisplayer( int $code ): void
	{
		if( isset( static::$displayers[$code] ) ) {
			unset( static::$displayers[$code] );
		}
	}


	/**
	 * @param int $code
	 * @param bool $application_end
	 */
	public static function handle( int $code, bool $application_end = true ): void
	{
		if( isset( static::$handlers[$code] ) ) {
			$handler = static::$handlers[$code];

			$handler( $code );
		}

		Http_Headers::response( $code );

		ErrorPages::display( $code );

		if( !$application_end ) {
			Application::end();
		}
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleServiceUnavailable( bool $application_end = true ): void
	{
		static::handle( Http_Headers::CODE_503_SERVICE_UNAVAILABLE, $application_end );
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleInternalServerError( bool $application_end = true ): void
	{
		static::handle( Http_Headers::CODE_500_INTERNAL_SERVER_ERROR, $application_end );
	}


	/**
	 * @param bool $application_end
	 */
	public static function handleUnauthorized( bool $application_end = true ): void
	{
		static::handle( Http_Headers::CODE_401_UNAUTHORIZED, $application_end );
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleNotFound( bool $application_end = true ): void
	{
		static::handle( Http_Headers::CODE_404_NOT_FOUND, $application_end );
	}

	/**
	 *
	 * @param int $code
	 *
	 * @return bool
	 */
	public static function display( int $code ): bool
	{
		if( isset( static::$displayers[$code] ) ) {
			$displayer = static::$displayers[$code];

			$displayer( $code );

			return true;
		}

		$path = static::getErrorPageFilePath( $code );
		if( !$path ) {
			return false;
		}

		if( ob_get_level() ) {
			ob_end_clean();
			ob_implicit_flush();
		}

		/** @noinspection PhpIncludeInspection */
		require $path;

		return true;
	}

	/**
	 *
	 * @param int $code
	 *
	 * @return bool|string
	 */
	public static function getErrorPageFilePath( int $code ): bool|string
	{
		if( !static::$error_pages_dir ) {
			return false;
		}

		$code = (int)$code;
		$path = static::$error_pages_dir . $code . '.phtml';

		if(
			is_file( $path ) &&
			file_exists( $path ) &&
			is_readable( $path )
		) {
			return $path;
		}

		return false;

	}

}