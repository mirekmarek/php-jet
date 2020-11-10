<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\Data_Array;

class ClassCreator_Class_Constant extends BaseObject
{


	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var mixed
	 */
	protected $value = '';




	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __construct($name, $value)
	{
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name )
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( $value )
	{
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$res = '';

		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();

		$value = $this->value;
		if(is_array($value)) {

			$value = (new Data_Array($value))->export();

			$value = explode("\n", $value);

			foreach( $value as $i=>$v ) {
				if($i>0) {
					$value[$i] = $ident.$v;
				}
			}

			$value = implode("\n", $value);
		} else {
			$value = var_export( $value, true ).';';
		}

		$res .= $ident.'const '.$this->name.' = '.$value.$nl;

		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}


}