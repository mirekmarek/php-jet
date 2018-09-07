<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface BaseObject_Interface
{


	/**
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function objectHasProperty( $property_name );

	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectSetterMethodName( $property_name );


	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectGetterMethodName( $property_name );

}