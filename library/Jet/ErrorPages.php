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

	//TODO: handler (vcetne kodu ...)
	//TODO: fallback callbacky
	//TODO: callbacky pro dalsi zpracovani

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