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
class ClassCreator_Attribute extends BaseObject
{
	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var array
	 */
	protected array $arguments = [];


	/**
	 * @param string $name
	 */
	public function __construct( string $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getArguments(): array
	{
		return $this->arguments;
	}


	/**
	 * @param string $argument
	 * @param mixed $value
	 */
	public function setArgument( string $argument, mixed $value ): void
	{
		$this->arguments[$argument] = $value;
	}

	/**
	 * @param int $pad
	 * @return string
	 */
	public function toString( int $pad = 0 ): string
	{
		$nl = ClassCreator_Class::getNl();
		$ident = ClassCreator_Class::getIndentation();

		$pad_str = str_pad( '', $pad * strlen( $ident ), $ident[0] );


		$res = $pad_str . '#[' . $this->name;

		if( count( $this->arguments ) ) {
			$res .= '(' . $nl;

			$c = 0;
			foreach( $this->arguments as $argument => $value ) {
				$c++;
				$res .= $pad_str . $ident . $argument . ': ' . static::toString_exportValue( $value, $pad_str );

				if( $c < count( $this->arguments ) ) {
					$res .= ',';
				}
				$res .= $nl;
			}

			$res .= $pad_str . ')';
		}

		return $res . ']' . $nl;
	}

	/**
	 * @param mixed $value
	 * @param string $pad_str
	 * @param int $level
	 *
	 * @return string
	 */
	public static function toString_exportValue( mixed $value, string $pad_str, int $level = 1 ): string
	{

		if( is_bool( $value ) ) {
			return $value ? 'true' : 'false';
		}

		if( is_int( $value ) || is_float( $value ) ) {
			return $value;
		}

		if( is_object( $value ) ) {
			$value = (string)$value;
		}

		if( is_string( $value ) ) {
			if( str_contains( $value, '::' ) ) {
				return $value;
			} else {
				return var_export( $value, true );
			}
		}

		$nl = ClassCreator_Class::getNl();
		$ident = ClassCreator_Class::getIndentation();

		$tab = '';
		$tab .= str_repeat( $ident, $level );

		$res = '[' . $nl;

		$i = 0;
		foreach( $value as $key => $v ) {
			$i++;
			if( is_string( $key ) ) {
				if( str_contains( $key, '::' ) ) {
					$res .= $pad_str . $tab . $ident . $key . ' => ' . static::toString_exportValue( $v, $pad_str, $level + 1 );

				} else {
					$res .= $pad_str . $tab . $ident . "'" . $key . "'" . ' => ' . static::toString_exportValue( $v, $pad_str, $level + 1 );
				}
			} else {
				$res .= $pad_str . $tab . $ident . static::toString_exportValue( $v, $pad_str, $level + 1 );
			}

			if( $i < count( $value ) ) {
				$res .= ',' . $nl;
			} else {
				$res .= $nl;
			}
		}

		$res .= $pad_str . $tab . ']';

		return $res;
	}


}