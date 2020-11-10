<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

class ClassCreator_Annotation extends BaseObject {

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 *
	 * @param string $prefix
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($prefix, $name, $value)
	{
		$this->prefix = $prefix;
		$this->name = $name;
		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
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
	public function setName($name)
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
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$value = $this->value;

		if(is_array( $value )) {
			$value = $this->arrayToString( $value );
		}

		return '@'.$this->prefix.':'.$this->name.' = '.$value;
	}

	/**
	 * @param array $value
	 *
	 * @return string
	 */
	public function arrayToString( array $value )
	{

		$res = [];

		foreach( $value as $k=>$v ) {
			if(is_array( $v )) {
				$v = $this->arrayToString( $v );
			}

			if(is_int($k)) {
				$res[] = $v;

			} else {
				$res[] = var_export($k, true).' => '.$v;
			}
		}

		$res = '['.implode(', ', $res).']';

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