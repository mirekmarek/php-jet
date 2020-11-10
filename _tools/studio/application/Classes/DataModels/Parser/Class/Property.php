<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;


class DataModels_Parser_Class_Property {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var DataModels_Parser_Parameter[]
	 */
	protected $parameters = [];

	/**
	 * @var string
	 */
	protected $declared_in_class = '';

	/**
	 * @var bool
	 */
	protected $is_inherited = false;

	/**
	 * @var string
	 */
	protected $inherited_class_name = '';

	/**
	 * @var bool
	 */
	protected $overload = false;

	/**
	 *
	 * @param string $name
	 * @param string $declared_in_class
	 */
	public function __construct( $name, $declared_in_class )
	{
		$this->name = $name;
		$this->declared_in_class = $declared_in_class;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $declared_in_class
	 */
	public function setDeclaredInClass( $declared_in_class )
	{
		$this->declared_in_class = $declared_in_class;
	}

	/**
	 * @return string
	 */
	public function getDeclaredInClass()
	{
		return $this->declared_in_class;
	}

	/**
	 * @return bool
	 */
	public function isInherited()
	{
		return $this->is_inherited;
	}

	/**
	 * @param bool $is_inherited
	 */
	public function setIsInherited( $is_inherited )
	{
		$this->is_inherited = $is_inherited;
	}

	/**
	 * @return string
	 */
	public function getInheritedClassName()
	{
		return $this->inherited_class_name;
	}

	/**
	 * @param string $inherited_class_name
	 */
	public function setInheritedClassName( $inherited_class_name )
	{
		$this->inherited_class_name = $inherited_class_name;
	}

	/**
	 * @return bool
	 */
	public function isOverload()
	{
		return $this->overload;
	}

	/**
	 * @param bool $overload
	 */
	public function setOverload( $overload )
	{
		$this->overload = $overload;
	}



	/**
	 * @param string $name
	 * @param DataModels_Parser_Parameter $param
	 */
	public function setParameter( $name, DataModels_Parser_Parameter $param )
	{
		$this->parameters[$name] = $param;
	}

	/**
	 * @return DataModels_Parser_Parameter[]
	 */
	public function getParameters()
	{
		return $this->parameters;
	}



}
