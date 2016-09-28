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

class Translator_Factory {


	/**
	 * Returns translator backend configuration instance
	 *
	 * @param string $type
	 * @param bool $soft_mode @see Config
	 *
	 * @return Translator_Backend_Config_Abstract
	 */
	public static function getBackendConfigInstance( $type, $soft_mode=false ){
		$class_name = JET_TRANSLATOR_BACKEND_CLASS_NAME_PREFIX.$type.'_Config';

		return new $class_name($soft_mode);
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


		$class_name = JET_TRANSLATOR_BACKEND_CLASS_NAME_PREFIX.$type;

		return new $class_name( $backend_config );
	}
}