<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;

/**
 *
 */
class ClassCreator_Annotation extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $prefix = '';

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var mixed
	 */
	protected mixed $value = null;

	/**
	 *
	 * @param string $prefix
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct( string $prefix, string $name, mixed $value )
	{
		$this->prefix = $prefix;
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getPrefix(): string
	{
		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix( string $prefix ): void
	{
		$this->prefix = $prefix;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getValue(): mixed
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( mixed $value ): void
	{
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		$value = $this->value;

		if( is_array( $value ) ) {
			$value = $this->arrayToString( $value );
		}

		return '@' . $this->prefix . ':' . $this->name . ' = ' . $value;
	}

	/**
	 * @param array $value
	 *
	 * @return string
	 */
	public function arrayToString( array $value ): string
	{

		$res = [];

		foreach( $value as $k => $v ) {
			if( is_array( $v ) ) {
				$v = $this->arrayToString( $v );
			}

			if( is_int( $k ) ) {
				$res[] = $v;

			} else {
				$res[] = var_export( $k, true ) . ' => ' . $v;
			}
		}

		return '[' . implode( ', ', $res ) . ']';
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

}