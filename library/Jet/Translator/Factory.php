<?php
/**
 *
 *
 *
 * Translator instance factory
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Translator
 * @subpackage Translator_Factory
 */
namespace Jet;

class Translator_Factory extends Factory {
	const BASE_BACKEND_CLASS_NAME_PREFIX = "Jet\\Translator_Backend_";


	/**
	 * Returns translator backend configuration instance
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 */
	public static function getBackendConfigInstance( $type, $soft_mode=false ){
		$class_name = Factory::getClassName( self::BASE_BACKEND_CLASS_NAME_PREFIX.$type."_Config" );
		$instance = new $class_name($soft_mode);
		self::checkInstance(self::BASE_BACKEND_CLASS_NAME_PREFIX.$type."_Config", $instance);
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

		$class_name =  Factory::getClassName( self::BASE_BACKEND_CLASS_NAME_PREFIX.$type );
		$instance = new $class_name( $backend_config );
		self::checkInstance(self::BASE_BACKEND_CLASS_NAME_PREFIX.$type, $instance);
		return $instance;
	}
}