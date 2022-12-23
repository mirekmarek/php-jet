<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use Error;

/**
 *
 */
abstract class BaseObject
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
		return 'set' . str_replace( '_', '', $property_name );
	}

	/**
	 * @param string $property_name
	 *
	 * @return string
	 */
	public function objectGetterMethodName( string $property_name ): string
	{
		return 'get' . str_replace( '_', '', $property_name );
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
			if( str_starts_with( $k, '__' ) ) {
				continue;
			}
			$r[$k] = $v;
		}

		return $r;
	}
}