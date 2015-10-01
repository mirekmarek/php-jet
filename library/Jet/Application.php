<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 * @JetApplication_Signals:signal = '/application/started'
 * @JetApplication_Signals:signal = '/application/ended'
 *
 */
class Application extends Object {

	/**
	 * @var bool
	 */
	protected static $do_not_end = false;

	/**
	 * @var Application_Config
	 */
	protected static $config = null;

	/**
	 *
	 */
	public static function installCommonDictionaries() {
		$dictionaries_path = JET_APPLICATION_PATH."_install/dictionaries/";

		$list = IO_Dir::getList( $dictionaries_path, '*.php' );

		$tr_backend_type = 'PHPFiles';

		$backend = Translator_Factory::getBackendInstance( $tr_backend_type );

		foreach( $list as $path=>$file_name ) {
			list($locale) = explode('.', $file_name);
			$locale = new Locale($locale);

			$dictionary = $backend->loadDictionary( Tr::COMMON_NAMESPACE, $locale, $path );

			$backend->saveDictionary( $dictionary );
		}
	}

	/**
	 * Start application
	 *
	 * @static
	 *
	 * @param string $configuration_name
	 *
	 * @throws Application_Exception
	 */
	public static function start( $configuration_name ){

		Debug_Profiler::MainBlockStart('Application init');

		if( strpos($configuration_name, '.')!==false ) {
			throw new Application_Exception(
				'Invalid configuration name \''.$configuration_name.'\'',
				Application_Exception::CODE_INVALID_CONFIGURATION_NAME

			);
		}

		$config_file_path = JET_CONFIG_PATH . $configuration_name.'.php';

		Debug_Profiler::blockStart('Configuration init');
			Config::setApplicationConfigFilePath( $config_file_path );
			static::getConfig();
		Debug_Profiler::blockEnd('Configuration init');

		Debug_Profiler::blockStart('Http request init');
			Http_Request::initialize( JET_HIDE_HTTP_REQUEST );
		Debug_Profiler::blockEnd('Http request init');

		Debug_Profiler::MainBlockEnd('Application init');

		$app = new self();
		$app->sendSignal('/application/started');

	}

	/**
	 * @return Application_Config|null
	 */
	public static function getConfig() {
		if(!static::$config) {
			static::$config = new Application_Config();
		}

		return static::$config;
	}

	/**
	 * @static
	 *
	 */
	public static function end(){
		if(!static::$do_not_end) {
			exit();
		}

		$app = new self();
		$app->sendSignal('/application/ended');
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
	public static function getIsDebugMode(){
		return defined('JET_DEVEL_MODE') && JET_DEVEL_MODE;
	}

}