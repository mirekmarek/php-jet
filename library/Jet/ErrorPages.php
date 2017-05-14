<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $error_pages_dir;

	/**
	 * @var callable[]
	 */
	protected static $callbacks = [];

	/**
	 * @var callable[]
	 */
	protected static $fallback = [];

	/**
	 * @param int      $code
	 * @param callable $callback
	 */
	public static function registerCallback( $code, callable $callback)
	{
		static::$callbacks[$code] = $callback;

	}

	/**
	 * @param int $code
	 */
	public function unRegisterCallback( $code )
	{
		if(isset(static::$callbacks[$code])) {
			unset(static::$callbacks[$code]);
		}
	}


	/**
	 * @param int      $code
	 * @param callable $callback
	 */
	public static function registerFallback( $code, callable $callback)
	{
		static::$fallback[$code] = $callback;

	}

	/**
	 * @param int $code
	 */
	public function unRegisterFallback( $code )
	{
		if(isset(static::$fallback[$code])) {
			unset(static::$fallback[$code]);
		}
	}


	/**
	 * @param int  $code
	 * @param bool $application_end
	 */
	public static function handle( $code, $application_end = true )
	{
		if(isset(static::$callbacks[$code])) {
			$callback = static::$callbacks[$code];

			$callback( $code );
		}

		Http_Headers::response( $code );

		if( !ErrorPages::display( $code ) ) {
			if(isset(static::$fallback[$code])) {
				$fallback = static::$fallback[$code];

				$fallback( $code );
			}
		}


		if(!$application_end) {
			Application::end();
		}
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleServiceUnavailable( $application_end = true )
	{
		static::handle(Http_Headers::CODE_503_SERVICE_UNAVAILABLE, $application_end);
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleInternalServerError( $application_end = true )
	{
		static::handle(Http_Headers::CODE_500_INTERNAL_SERVER_ERROR, $application_end);
	}



	/**
	 * @param bool $application_end
	 */
	public static function handleUnauthorized( $application_end = true )
	{
		static::handle(Http_Headers::CODE_401_UNAUTHORIZED, $application_end);
	}

	/**
	 * @param bool $application_end
	 */
	public static function handleNotFound( $application_end = true )
	{
		static::handle(Http_Headers::CODE_404_NOT_FOUND, $application_end);
	}

	/**
	 *
	 * @param int $code
	 *
	 * @return bool
	 */
	public static function display( $code )
	{
		$path = static::getErrorPageFilePath( $code );
		if( !$path ) {
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
	 *
	 * @param int $code
	 *
	 * @return bool|string
	 */
	public static function getErrorPageFilePath( $code )
	{
		if( !static::$error_pages_dir ) {
			return false;
		}

		$code = (int)$code;
		$path = static::$error_pages_dir.$code.'.phtml';

		if(
			is_file( $path )&&
			file_exists( $path )&&
			is_readable( $path )
		) {
			return $path;
		}

		return false;

	}

	/**
	 *
	 * @return string
	 */
	public static function getErrorPagesDir()
	{
		return static::$error_pages_dir;
	}

	/**
	 *
	 *
	 * @param string $error_pages_dir
	 * @param bool   $check_path (optional, default: true)
	 *
	 * @throws ErrorPages_Exception
	 */
	public static function setErrorPagesDir( $error_pages_dir, $check_path = true )
	{
		$error_pages_dir = rtrim( $error_pages_dir, '/\\'.DIRECTORY_SEPARATOR ).DIRECTORY_SEPARATOR;

		if(
			$check_path &&
			!is_dir( $error_pages_dir )
		) {
			throw new ErrorPages_Exception(
				'Error pages directory \''.$error_pages_dir.'\' does not exist',
				ErrorPages_Exception::CODE_INVALID_ERROR_PAGES_DIR_PATH
			);
		}

		static::$error_pages_dir = $error_pages_dir;
	}
}