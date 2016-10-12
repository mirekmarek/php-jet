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
 * @package Object
 */

namespace Jet;

trait BaseObject_Trait_MagicSet {

	/**
	 * Setter for protected properties
	 *
	 * @param string $key
	 * @param $value
	 *
	 * @throws BaseObject_Exception
	 *
	 * @param mixed $value
	 */
	public function __set( $key, $value ) {

		if(!property_exists($this, $key)) {
			throw new BaseObject_Exception(
				'Undefined class property '.get_class($this).'->'.$key,
				BaseObject_Exception::CODE_UNDEFINED_PROPERTY
			);
		}

		throw new BaseObject_Exception(
			'Access to protected class property '.get_class($this).'->'.$key,
			BaseObject_Exception::CODE_ACCESS_PROTECTED_PROPERTY
		);
	}

}