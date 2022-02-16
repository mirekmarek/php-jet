<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\Data_Array;

/**
 *
 */
class ClassCreator_Class_Constant extends BaseObject
{


	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var mixed
	 */
	protected mixed $value = '';


	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct( string $name, mixed $value )
	{
		$this->name = $name;
		$this->value = $value;
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
		$res = '';

		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$value = $this->value;
		if( is_array( $value ) ) {

			$value = (new Data_Array( $value ))->export();

			$value = explode( "\n", $value );

			foreach( $value as $i => $v ) {
				if( $i > 0 ) {
					$value[$i] = $ident . $v;
				}
			}

			$value = implode( "\n", $value );
		} else {
			$value = var_export( $value, true ) . ';';
		}

		$res .= $ident . 'const ' . $this->name . ' = ' . $value . $nl;

		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}


}