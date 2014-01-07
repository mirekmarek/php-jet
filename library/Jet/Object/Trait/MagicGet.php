<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Object
 */

namespace Jet;

//We do not have multiple inheritance in PHP :-(
trait Object_Trait_MagicGet {

	/**
	 * Getter for protected properties
	 *
	 * @param string $key
	 * @throws Object_Exception
	 *
	 */
	public function __get( $key ) {

		if(!property_exists($this, $key)) {
			throw new Object_Exception(
				"Undefined class property ".get_class($this)."->{$key}",
				Object_Exception::CODE_UNDEFINED_PROPERTY
			);
		}

		throw new Object_Exception(
			"Access to protected class property ".get_class($this)."->{$key}",
			Object_Exception::CODE_ACCESS_PROTECTED_PROPERTY
		);
	}

}