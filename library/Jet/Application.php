<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Application
 *
 *
 */
class Application extends BaseObject
{

	const CONFIG_FILE_NAME = 'application';

	/**
	 * @var bool
	 */
	protected static $do_not_end = false;

	/**
	 * @var string
	 */
	protected static $config_file_path;

	/**
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 * @return string
	 */
	public static function getConfigFilePath()
	{
		if( !self::$config_file_path ) {
			static::$config_file_path = JET_PATH_CONFIG.JET_CONFIG_ENVIRONMENT.'/'.static::CONFIG_FILE_NAME.'.php';
		}

		return self::$config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public static function setConfigFilePath( $config_file_path )
	{
		self::$config_file_path = $config_file_path;
	}


	/**
	 *
	 * @throws Application_Exception
	 */
	public static function start()
	{

		Debug_Profiler::MainBlockStart( 'Application init' );


		Debug_Profiler::blockStart( 'Http request init' );
		if(defined('JET_HIDE_HTTP_REQUEST')) {
			Http_Request::initialize( JET_HIDE_HTTP_REQUEST );
		} else {
			Http_Request::initialize();
		}
		Debug_Profiler::blockEnd( 'Http request init' );

		Debug_Profiler::MainBlockEnd( 'Application init' );

	}

	/**
	 * @return Application_Config|null
	 */
	public static function getConfig()
	{
		if( !static::$config ) {
			Debug_Profiler::blockStart( 'Configuration init' );
			static::$config = new Application_Config();
			Debug_Profiler::blockEnd( 'Configuration init' );
		}

		return static::$config;
	}

	/**
	 *
	 * @param callable    $after_initialization
	 * @param string|null $URL (optional; URL to dispatch; default: null = current URL)
	 *
	 */
	public static function runMvc( callable $after_initialization=null, $URL = null )
	{
		$router = Mvc::getCurrentRouter();

		if( !$URL ) {
			$URL = Http_Request::getURL();
		}

		$router->initialize( $URL );

		if( $after_initialization ) {
			$after_initialization();
		}

		if( $router->getIsRedirect() ) {
			$router->handleRedirect();
		}

		$site = Mvc::getCurrentSite();

		ErrorPages::setErrorPagesDir( $site->getPagesDataPath( Mvc::getCurrentLocale() ) );


		if( !$site->getIsActive() ) {
			ErrorPages::handleServiceUnavailable( false );

			return;
		}

		$locale = Mvc::getCurrentLocale();
		if( !$site->getLocalizedData( $locale )->getIsActive() ) {
			ErrorPages::handleNotFound( false );

			return;
		}

		if( $router->getIs404() ) {
			ErrorPages::handleNotFound( false );

			return;
		}

		$page = Mvc::getCurrentPage();

		if( !$page->getIsActive() ) {
			ErrorPages::handleNotFound( false );

			return;
		}


		if( $router->getLoginRequired() ) {
			Auth::getAuthController()->handleLogin();

			return;
		}

		if( !$page->getAccessAllowed() ) {
			ErrorPages::handleUnauthorized( false );

			return;
		}

		if( $router->getIsFile() ) {
			$page->handleFile( $router->getFileName() );

			return;
		}


		if( $page->getIsDirectOutput() ) {
			$page->handleHttpHeaders();
			$page->handleDirectOutput();
		} else {
			$result = $page->render();

			$page->handleHttpHeaders();

			echo $result;
		}
	}

	/**
	 *
	 */
	public static function end()
	{

		if( !static::$do_not_end ) {
			exit();
		}
	}

	/**
	 * Useful for tests
	 *
	 */
	public static function doNotEnd()
	{
		static::$do_not_end = true;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getIsInDevelMode()
	{
		return defined( 'JET_DEVEL_MODE' )&&JET_DEVEL_MODE;
	}

}