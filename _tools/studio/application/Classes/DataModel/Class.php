<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use \ReflectionClass;

/**
 *
 */
class DataModel_Class {

	/**
	 * @var string
	 */
	protected $script_path = '';

	/**
	 * @var ReflectionClass
	 */
	protected $reflection;

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var string
	 */
	protected $class_name = '';

	/**
	 * @var array
	 */
	protected $_parents;

	/**
	 * @var DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	protected $definition;

	/**
	 * @var string
	 */
	protected $error = '';

	/**
	 * @var bool
	 */
	protected $is_new = false;

	/**
	 * @param string $script_path
	 * @param string $namespace
	 * @param string $class_name
	 * @param ReflectionClass|null $reflection
	 */
	public function __construct( $script_path, $namespace, $class_name, ReflectionClass $reflection=null )
	{
		$this->script_path = $script_path;
		$this->reflection = $reflection;
		$this->namespace = $namespace;
		$this->class_name = $class_name;
	}

	/**
	 * @return bool
	 */
	public function isIsNew()
	{
		return $this->is_new;
	}

	/**
	 * @param bool $is_new
	 */
	public function setIsNew( $is_new )
	{
		$this->is_new = $is_new;
	}



	/**
	 * @return string
	 */
	public function getScriptPath()
	{
		return $this->script_path;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class_name;
	}

	/**
	 * @return ReflectionClass
	 */
	public function getReflection()
	{
		return $this->reflection;
	}



	/**
	 * @return string
	 */
	public function getFullClassName()
	{
		return $this->namespace.'\\'.$this->class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN
	 */
	public function getDefinition()
	{
		return $this->definition;
	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|DataModel_Definition_Model_Related_MtoN $definition
	 */
	public function setDefinition( $definition )
	{
		$definition->setClass( $this );

		$this->definition = $definition;
	}

	/**
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @param string $error
	 */
	public function setError( $error )
	{
		$this->error = $error;
	}

	/**
	 * @return array
	 */
	public function getImplements()
	{
		if(!$this->reflection) {
			return [];
		}
		return $this->reflection->getInterfaceNames();
	}

	/**
	 * @return bool
	 */
	public function isAbstract()
	{
		if(!$this->reflection) {
			return false;
		}

		return $this->reflection->isAbstract();
	}

	/**
	 * @return string
	 */
	public function getExtends()
	{
		if(!$this->reflection) {
			return '';
		}

		return $this->reflection->getParentClass()->getName();
	}

	/**
	 * @return array
	 */
	public function getParents()
	{
		if($this->_parents===null) {
			$this->_parents = [];

			$getParent = function( DataModel_Class $class ) use ( &$getParent) {
				if($class->getExtends()) {
					$e_class = DataModels::getClass( $class->getExtends() );
					if($e_class) {
						$this->_parents[] = $e_class->getFullClassName();
						$getParent( $e_class );
					}
				}

			};

			$getParent( $this );
		}


		return $this->_parents;
	}


	/**
	 * @param DataModel_Class $class
	 *
	 * @return bool
	 */
	public function isDescendantOf( DataModel_Class $class )
	{
		$parents = $this->getParents();

		return in_array( $class->getFullClassName(), $parents );
	}

	/**
	 * @param string $property_name
	 *
	 * @return false|string
	 */
	public function getPropertyDeclaringClass( $property_name )
	{
		$parents = $this->getParents();

		foreach($parents as $class_name) {
			$class = DataModels::getClass($class_name);

			if(!$class) {
				continue;
			}

			if($class->getReflection()->hasProperty($property_name)) {
				return $class_name;
			}
		}

		return false;
	}
}
