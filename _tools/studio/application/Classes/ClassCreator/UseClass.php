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
class ClassCreator_UseClass extends BaseObject
{

	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * @var string
	 */
	protected string $class = '';

	/**
	 * @var string
	 */
	protected string $use_as = '';

	/**
	 *
	 * @param string $namespace
	 * @param string $class
	 * @param string $use_as
	 */
	public function __construct( string $namespace,
	                             string $class,
	                             string $use_as = '' )
	{
		$this->setNamespace( $namespace );
		$this->class = $class;
		$this->use_as = $use_as;
	}

	/**
	 * @param string $class_name
	 *
	 * @return ClassCreator_UseClass
	 */
	public static function createByClassName( string $class_name ): ClassCreator_UseClass
	{
		$_cn = explode( '\\', $class_name );

		$class_name = array_pop( $_cn );
		$namespace = implode( '\\', $_cn );

		return new static( $namespace, $class_name );
	}

	/**
	 * @return string
	 */
	public function getKey(): string
	{
		return $this->namespace . '\\' . $this->class;
	}


	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace( string $namespace ): void
	{
		$this->namespace = rtrim( $namespace, '\\' );
	}

	/**
	 * @return string
	 */
	public function getClass(): string
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 */
	public function setClass( string $class ): void
	{
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getUseAs(): string
	{
		return $this->use_as;
	}

	/**
	 * @param string $use_as
	 */
	public function setUseAs( string $use_as ): void
	{
		$this->use_as = $use_as;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		$res = 'use ' . $this->namespace . '\\' . $this->class;

		if( $this->getUseAs() ) {
			$res .= ' as ' . $this->use_as;
		}

		$res .= ';';

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