<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use ReflectionClass;

/**
 *
 */
class DataModel_Class
{

	/**
	 * @var string
	 */
	protected string $script_path = '';

	/**
	 * @var ?ReflectionClass
	 */
	protected ?ReflectionClass $reflection = null;

	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * @var string
	 */
	protected string $class_name = '';

	/**
	 * @var array|null
	 */
	protected array|null $_parents = null;

	/**
	 * @var DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|null
	 */
	protected DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN|null $definition = null;

	/**
	 * @var string
	 */
	protected string $error = '';

	/**
	 * @var bool
	 */
	protected bool $is_new = false;

	/**
	 * @param string $script_path
	 * @param string $namespace
	 * @param string $class_name
	 * @param ReflectionClass|null $reflection
	 */
	public function __construct( string $script_path, string $namespace, string $class_name, ReflectionClass $reflection = null )
	{
		$this->script_path = $script_path;
		$this->reflection = $reflection;
		$this->namespace = $namespace;
		$this->class_name = $class_name;
	}

	/**
	 * @return bool
	 */
	public function isIsNew(): bool
	{
		return $this->is_new;
	}

	/**
	 * @param bool $is_new
	 */
	public function setIsNew( bool $is_new ): void
	{
		$this->is_new = $is_new;
	}


	/**
	 * @return string
	 */
	public function getScriptPath(): string
	{
		return $this->script_path;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->class_name;
	}

	/**
	 * @return ReflectionClass
	 */
	public function getReflection(): ReflectionClass
	{
		return $this->reflection;
	}


	/**
	 * @return string
	 */
	public function getFullClassName(): string
	{
		return $this->namespace . '\\' . $this->class_name;
	}

	/**
	 * @return DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	 */
	public function getDefinition(): DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN
	{
		return $this->definition;
	}

	/**
	 * @param DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $definition
	 */
	public function setDefinition( DataModel_Definition_Model_Main|DataModel_Definition_Model_Related_1to1|DataModel_Definition_Model_Related_1toN $definition ) : void
	{
		$definition->setClass( $this );

		$this->definition = $definition;
	}

	/**
	 * @return string
	 */
	public function getError(): string
	{
		return $this->error;
	}

	/**
	 * @param string $error
	 */
	public function setError( string $error ): void
	{
		$this->error = $error;
	}

	/**
	 * @return array
	 */
	public function getImplements(): array
	{
		if( !$this->reflection ) {
			return [];
		}
		return $this->reflection->getInterfaceNames();
	}

	/**
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		if( !$this->reflection ) {
			return false;
		}

		return $this->reflection->isAbstract();
	}

	/**
	 * @return string
	 */
	public function getExtends(): string
	{
		if( !$this->reflection ) {
			return '';
		}

		return $this->reflection->getParentClass()->getName();
	}

	/**
	 * @return array
	 */
	public function getParents(): array
	{
		if( $this->_parents === null ) {
			$this->_parents = [];

			$getParent = function( DataModel_Class $class ) use ( &$getParent ) {
				if( $class->getExtends() ) {
					$e_class = DataModels::getClass( $class->getExtends() );
					if( $e_class ) {
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
	public function isDescendantOf( DataModel_Class $class ): bool
	{
		$parents = $this->getParents();

		return in_array( $class->getFullClassName(), $parents );
	}

	/**
	 * @param string $property_name
	 *
	 * @return bool|string
	 */
	public function getPropertyDeclaringClass( string $property_name ): bool|string
	{
		$parents = $this->getParents();

		foreach( $parents as $class_name ) {
			$class = DataModels::getClass( $class_name );

			if( !$class ) {
				continue;
			}

			if( $class->getReflection()->hasProperty( $property_name ) ) {
				return $class_name;
			}
		}

		return false;
	}
}
