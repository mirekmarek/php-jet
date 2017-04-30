<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

//We don't have multiple inheritance in PHP :-(
/**
 * Interface BaseObject_Interface
 * @package Jet
 */
interface BaseObject_Interface {


	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep();

	/**
	 * Getter for protected properties
	 *
	 * @param string $key
	 * @throws BaseObject_Exception
	 *
	 */
	public function __get( $key );

	/**
	 * Setter for protected properties
	 *
	 * @param string $key
	 * @param mixed $value
	 * @throws BaseObject_Exception
	 */
	public function __set( $key, $value );

	/**
	 *
	 */
	public function __clone();

	/**
	 * @param $property_name
	 *
	 * @return bool
	 */
	public function getObjectClassHasProperty($property_name );

	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getSetterMethodName( $property_name );


	/**
	 * @param $property_name
	 *
	 * @return string
	 */
	public function getGetterMethodName( $property_name );

}