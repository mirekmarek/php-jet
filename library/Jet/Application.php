<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Application
 */
namespace Jet;

/**
 * Class Application
 *
 *
 */
class Application extends BaseObject {

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
		if(!self::$config_file_path) {
			static::$config_file_path = JET_CONFIG_PATH . JET_APPLICATION_CONFIGURATION_NAME.'.php';
		}

		return self::$config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public static function setConfigFilePath($config_file_path)
	{
		self::$config_file_path = $config_file_path;
	}



	/**
	 * Start application
	 *
	 * @static
	 *
	 * @throws Application_Exception
	 */
	public static function start(){

		Debug_Profiler::MainBlockStart('Application init');


		Debug_Profiler::blockStart('Http request init');
			Http_Request::initialize( JET_HIDE_HTTP_REQUEST );
		Debug_Profiler::blockEnd('Http request init');

		Debug_Profiler::MainBlockEnd('Application init');

	}

	/**
	 * @return Application_Config|null
	 */
	public static function getConfig() {
		if(!static::$config) {
			Debug_Profiler::blockStart('Configuration init');
			static::$config = new Application_Config();
			Debug_Profiler::blockEnd('Configuration init');
		}

		return static::$config;
	}

	/**
	 * Initializes system and run dispatch.
	 *
	 * @static
	 *
	 * @param string|null $URL (optional; URL to dispatch; default: null = current URL)
	 * @param bool|null $cache_enabled (optional; default: null = by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 */
	public static function runMvc( $URL=null, $cache_enabled=null  ) {
		$router = Mvc::getCurrentRouter();

		if(!$URL) {
			$URL = Http_Request::getURL();
		}

		$router->initialize($URL, $cache_enabled);


		$site = Mvc::getCurrentSite();
		$locale = Mvc::getCurrentLocale();
		$page = Mvc::getCurrentPage();

		if( $page && ($output=$page->getOutput() )!==null ) {
			echo $output;

			return;
		}

		if($router->getIsRedirect()) {
			$router->handleRedirect();
		}

		$site->setupErrorPagesDir();

		if( !$site->getIsActive() ) {
			$site->handleDeactivatedSite();
			return;
		}

		if( !$site->getLocalizedData($locale)->getIsActive() ) {
			$site->handleDeactivatedLocale();
			return;
		}

		if( $router->getIs404() ) {
			$site->handle404();
			return;
		}

		if($router->getLoginRequired()) {
			Auth::getCurrentAuthController()->handleLogin();
			return;
		}

		if( !Mvc::getCurrentPage()->getAccessAllowed() ) {
			$site->handleAccessDenied();
			return;
		}

		if( $router->getIsFile() ) {
			$page->handleFile( $router->getFileName() );
			return;
		}

		$output = $page->render();

		echo $output;

	}

	/**
	 * @static
	 *
	 */
	public static function end(){

		if(!static::$do_not_end) {
			exit();
		}
	}

	/**
	 * Useful for tests
	 *
	 */
	public static function doNotEnd() {
		static::$do_not_end = true;
	}

	/**
	 * @static
	 *
	 * @return bool
	 */
	public static function getIsInDevelMode(){
		return defined('JET_DEVEL_MODE') && JET_DEVEL_MODE;
	}

}