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
		if( !static::$config_file_path ) {
			static::$config_file_path = JET_PATH_CONFIG.JET_CONFIG_ENVIRONMENT.'/application.php';
		}

		return static::$config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public static function setConfigFilePath( $config_file_path )
	{
		static::$config_file_path = $config_file_path;
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
	 * @param string|null $URL (optional; URL to dispatch; default: null = current URL)
	 *
	 */
	public static function runMvc( $URL = null )
	{
		Debug_Profiler::blockStart( 'MVC router - init and resolve' );

		$router = Mvc::getRouter();

		$router->resolve( $URL );

		Debug_Profiler::blockEnd( 'MVC router - init and resolve' );

		if( $router->getIsRedirect() ) {

			Http_Headers::redirect(
				$router->getRedirectType(),
				$router->getRedirectTargetURL(),
				[],
				false
			);

			return;
		}

		$site = Mvc::getCurrentSite();


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
			Auth::handleLogin();

			return;
		}

		if( !$page->getAccessAllowed() ) {
			ErrorPages::handleUnauthorized( false );

			return;
		}

		if( $router->getIsFile() ) {
			$page->handleFile( $router->getFilePath() );

			return;
		}


		if( $page->getIsSubApp() ) {
			$page->handleHttpHeaders();
			$page->handleSubApp();

			return;
		}

		$result = $page->render();

		$page->handleHttpHeaders();

		echo $result;

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