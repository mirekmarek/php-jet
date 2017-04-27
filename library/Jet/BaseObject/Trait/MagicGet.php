<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//We do not have multiple inheritance in PHP :-(
/**
 * Class BaseObject_Trait_MagicGet
 * @package Jet
 */
trait BaseObject_Trait_MagicGet {

	/**
	 * Getter for protected properties
	 *
	 * @param string $key
	 * @throws BaseObject_Exception
	 *
	 */
	public function __get( $key ) {

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