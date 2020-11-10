<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;

class ClassCreator_UseClass extends BaseObject
{

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var string
	 */
	protected $class = '';

	/**
	 * @var string
	 */
	protected $use_as = '';

	/**
	 *
	 * @param string $namespace
	 * @param string $class
	 * @param string $use_as
	 */
	public function __construct($namespace, $class, $use_as='' )
	{
		$this->setNamespace( $namespace );
		$this->class = $class;
		$this->use_as = $use_as;
	}

	/**
	 * @param $class_name
	 *
	 * @return ClassCreator_UseClass
	 */
	public static function createByClassName( $class_name )
	{
		$_cn = explode('\\', $class_name);

		$class_name = array_pop( $_cn );
		$namespace = implode('\\', $_cn);

		$i = new static( $namespace, $class_name );

		return $i;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->namespace.'\\'.$this->class;
	}


	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace($namespace)
	{
		$this->namespace = rtrim($namespace, '\\');
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getUseAs()
	{
		return $this->use_as;
	}

	/**
	 * @param string $use_as
	 */
	public function setUseAs($use_as)
	{
		$this->use_as = $use_as;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$res = 'use '.$this->namespace.'\\'.$this->class;

		if( $this->getUseAs() ) {
			$res .= ' as '.$this->use_as;
		}

		$res .= ';';

		return $res;
	}

	public function __toString()
	{
		return $this->toString();
	}



}