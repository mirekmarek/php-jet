<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

use Error;

/**
 *
 */
trait BaseObject_Trait
{
	/**
	 * @param string $property_name
	 *
	 * @return bool
	 */
	public function objectHasProperty( string $property_name ): bool
	{
		if(
			$property_name[0] == '_' ||
			!property_exists( $this, $property_name )
		) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectSetterMethodName( string $property_name ): string
	{
		$setter_method_name = 'set' . str_replace( '_', '', $property_name );

		return $setter_method_name;
	}

	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectGetterMethodName( string $property_name ): string
	{
		$setter_method_name = 'get' . str_replace( '_', '', $property_name );

		return $setter_method_name;
	}


	/**
	 * Default serialize rules (don't serialize __* properties)
	 *
	 * @return array
	 */
	public function __sleep(): array
	{
		$vars = [];
		foreach(get_object_vars($this) as $k=>$v) {
			if($k[0]!='_') {
				$vars[] = $k;
			}
		}

		return $vars;
	}

	/**
	 *
	 * @param string $key
	 *
	 * @throws BaseObject_Exception
	 *
	 */
	public function __get( string $key ): void
	{

		if( !property_exists( $this, $key ) ) {
			throw new BaseObject_Exception(
				'Undefined class property ' . get_class( $this ) . '->' . $key, BaseObject_Exception::CODE_UNDEFINED_PROPERTY
			);
		}

		throw new BaseObject_Exception(
			'Access to protected class property ' . get_class( $this ) . '->' . $key,
			BaseObject_Exception::CODE_ACCESS_PROTECTED_PROPERTY
		);
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @throws BaseObject_Exception
	 *
	 */
	public function __set( string $key, mixed $value ): void
	{

		if( !property_exists( $this, $key ) ) {
			throw new BaseObject_Exception(
				'Undefined class property ' . get_class( $this ) . '->' . $key, BaseObject_Exception::CODE_UNDEFINED_PROPERTY
			);
		}

		throw new BaseObject_Exception(
			'Access to protected class property ' . get_class( $this ) . '->' . $key,
			BaseObject_Exception::CODE_ACCESS_PROTECTED_PROPERTY
		);
	}

	/**
	 *
	 */
	public function __clone(): void
	{
		//debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		$properties = get_object_vars( $this );

		foreach( $properties as $key => $val ) {
			if( is_object( $val ) ) {
				try {
					$this->{$key} = clone $val;
				} catch( Error $e ) {
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array
	{
		$vars = get_object_vars( $this );

		$r = [];
		foreach( $vars as $k => $v ) {
			if( substr( $k, 0, 2 ) === '__' ) {
				continue;
			}
			$r[$k] = $v;
		}

		return $r;
	}


}