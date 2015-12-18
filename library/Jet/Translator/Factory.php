<?php
/**
 *
 *
 *
 * Translator instance factory
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Factory
 */
namespace Jet;

class Translator_Factory extends Factory {
	/**
	 * @var string
	 */
	protected static $backend_class_name_prefix = 'Translator_Backend_';

	/**
	 * @param string $backend_class_name_prefix
	 */
	public static function setBackendClassNamePrefix($backend_class_name_prefix) {
		static::$backend_class_name_prefix = $backend_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getBackendClassNamePrefix() {
		return static::$backend_class_name_prefix;
	}


	/**
	 * Returns translator backend configuration instance
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 */
	public static function getBackendConfigInstance( $type, $soft_mode=false ){
		$default_class_name = static::$backend_class_name_prefix.$type.'_Config';

		$class_name = static::getClassName( $default_class_name );
		$instance = new $class_name($soft_mode);
		//static::checkInstance( $default_class_name, $instance);
		return $instance;
	}

	/**
	 * Returns translator backend instance
	 *
	 * @param $type
	 * @param Translator_Backend_Config_Abstract $backend_config
	 *
	 *
	 * @return Translator_Backend_Abstract
	 */
	public static function getBackendInstance( $type, Translator_Backend_Config_Abstract $backend_config=null ){
		if(!$backend_config) {
			$backend_config = static::getBackendConfigInstance($type);
		}

		$_class_name = static::$backend_class_name_prefix.$type;

		$class_name =  static::getClassName( $_class_name );
		$instance = new $class_name( $backend_config );
		//static::checkInstance( $_class_name, $instance);
		return $instance;
	}
}