<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Factory
 */
namespace Jet;

class Auth_Factory {

	/**
	 *
	 * @param bool $soft_mode (optional, default:false)
	 *
	 * @return Auth_Config_Abstract
	 */
	public static function getConfigInstance( $soft_mode=false ) {
		$class_name =  JET_AUTH_CONFIG_CLASS;
		return new $class_name($soft_mode);

	}

}