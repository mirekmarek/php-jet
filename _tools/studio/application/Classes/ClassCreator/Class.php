<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio;

use Jet\BaseObject;
use Jet\BaseObject_Exception;

/**
 *
 */
class ClassCreator_Class extends BaseObject
{
	const VISIBILITY_PUBLIC = 'public';
	const VISIBILITY_PROTECTED = 'protected';
	const VISIBILITY_PRIVATE = 'private';

	protected static string $indentation = "\t";
	protected static string $nl = "\n";

	/**
	 * @var string
	 */
	protected string $namespace = '';

	/**
	 * @var ClassCreator_UseClass[]
	 */
	protected array $use = [];

	/**
	 * @var ClassCreator_Attribute[]
	 */
	protected array $attributes = [];


	/**
	 * @var bool
	 */
	protected bool $is_abstract = false;

	/**
	 * @var string
	 */
	protected string $name = '';

	/**
	 * @var string
	 */
	protected string $extends = '';

	/**
	 * @var array
	 */
	protected array $implements = [];

	/**
	 * @var ClassCreator_Class_Constant[]
	 */
	protected array $constants = [];

	/**
	 * @var ClassCreator_Class_Property[]
	 */
	protected array $properties = [];

	/**
	 * @var ClassCreator_Class_Method[]
	 */
	protected array $methods = [];

	/**
	 * @var array
	 */
	protected array $errors = [];

	/**
	 * @var array
	 */
	protected array $warnings = [];

	/**
	 * @return bool
	 */
	public function isAbstract(): bool
	{
		return $this->is_abstract;
	}

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( bool $is_abstract ): void
	{
		$this->is_abstract = $is_abstract;
	}

	/**
	 * @return ClassCreator_UseClass[]
	 */
	public function getUse(): array
	{
		return $this->use;
	}


	/**
	 * @return ClassCreator_Class_Constant[]
	 */
	public function getConstants(): array
	{
		return $this->constants;
	}

	/**
	 * @return ClassCreator_Class_Property[]
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 * @return ClassCreator_Class_Method[]
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}

	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method|null
	 */
	public function getMethod( string $name ): ClassCreator_Class_Method|null
	{
		if( !isset( $this->methods[$name] ) ) {
			return null;
		}

		return $this->methods[$name];
	}


	/**
	 * @return string
	 */
	public static function getIndentation(): string
	{
		return static::$indentation;
	}

	/**
	 * @param string $indentation
	 */
	public static function setIndentation( string $indentation ): void
	{
		static::$indentation = $indentation;
	}

	/**
	 * @return string
	 */
	public static function getNl(): string
	{
		return static::$nl;
	}

	/**
	 * @param string $nl
	 */
	public static function setNl( string $nl ): void
	{
		static::$nl = $nl;
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
		$this->namespace = $namespace;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFullName(): string
	{
		return $this->getNamespace() . '\\' . $this->getName();
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getExtends(): string
	{
		return $this->extends;
	}

	/**
	 * @param string $extends
	 */
	public function setExtends( string $extends ): void
	{
		$this->extends = $extends;
	}

	/**
	 * @param string $class_name
	 */
	public function addImplements( string $class_name ): void
	{
		$this->implements[] = $class_name;
	}

	/**
	 * @param ClassCreator_UseClass $use_class
	 */
	public function addUse( ClassCreator_UseClass $use_class ): void
	{
		foreach( $this->use as $e_use ) {
			if(
				$e_use->getClass() == $use_class->getClass()
			) {
				if( $e_use->getUseAs() != $use_class->getUseAs() ) {
					throw new BaseObject_Exception( 'Use class collision: ' . $e_use->getClass() . ' as ' . $e_use . ' VS ' . $use_class->getClass() . ' as ' . $use_class->getUseAs() );
				}

				return;
			}
		}

		$this->use[] = $use_class;
	}

	/**
	 * @param string $name
	 * @param string $argument
	 * @param mixed $argument_value
	 */
	public function setAttribute( string $name, string $argument, mixed $argument_value ): void
	{
		if( !isset( $this->attributes[$name] ) ) {
			$this->attributes[$name] = new ClassCreator_Attribute( $name );
		}

		$this->attributes[$name]->setArgument( $argument, $argument_value );
	}


	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return ClassCreator_Class_Constant
	 */
	public function createConstant( string $name, mixed $value ): ClassCreator_Class_Constant
	{
		$constant = new ClassCreator_Class_Constant( $name, $value );

		$this->addConstant( $constant );

		return $constant;
	}

	/**
	 * @param ClassCreator_Class_Constant $constant
	 */
	public function addConstant( ClassCreator_Class_Constant $constant ): void
	{
		if( isset( $this->constants[$constant->getName()] ) ) {
			throw new BaseObject_Exception( 'Constant ' . $constant->getName() . ' already defined' );
		}

		$this->constants[$constant->getName()] = $constant;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasConstant( string $name ): bool
	{
		return isset( $this->constants[$name] );
	}


	/**
	 * @param ClassCreator_Class_Property $property
	 */
	public function addProperty( ClassCreator_Class_Property $property ): void
	{
		if( isset( $this->properties[$property->getName()] ) ) {
			throw new BaseObject_Exception( 'Property ' . $property->getName() . ' already defined' );
		}

		$this->properties[$property->getName()] = $property;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasProperty( string $name ): bool
	{
		return isset( $this->properties[$name] );
	}

	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method
	 */
	public function createMethod( string $name ): ClassCreator_Class_Method
	{
		$method = new ClassCreator_Class_Method( $name );

		$this->addMethod( $method );

		return $method;
	}

	/**
	 * @param ClassCreator_Class_Method $method
	 */
	public function addMethod( ClassCreator_Class_Method $method ): void
	{
		if( isset( $this->methods[$method->getName()] ) ) {
			$this->addError( 'Method ' . $method->getName() . ' already defined' );
		}

		$this->methods[$method->getName()] = $method;

	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasMethod( string $name ): bool
	{
		return isset( $this->methods[$name] );
	}

	/**
	 * @return string
	 */
	public function generateClassAnnotation(): string
	{
		$nl = ClassCreator_Class::getNl();

		$res = '';

		$res .= '/**' . $nl;
		$res .= ' *' . $nl;
		$res .= ' */' . $nl;

		return $res;
	}

	/**
	 * @return ClassCreator_Attribute[]
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}


	/**
	 * @return string
	 */
	public function toString(): string
	{

		$res = '';

		$use_str = [];
		foreach( $this->use as $use ) {
			$use_str[] = $use->toString();
		}

		asort( $use_str );


		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();


		$res .= '/**' . $nl;
		$res .= ' * ' . $nl;
		$res .= ' */' . $nl;

		$res .= $nl;
		$res .= 'namespace ' . $this->getNamespace() . ';' . $nl;

		$res .= $nl;
		$res .= implode( $nl, $use_str ) . $nl;
		$res .= $nl;

		$res .= $this->generateClassAnnotation();

		foreach( $this->attributes as $attribute ) {
			$res .= $attribute->toString();
		}

		$res .= ($this->isAbstract() ? 'abstract ' : '') . 'class ' . $this->name;
		if( $this->extends ) {
			$res .= ' extends ' . $this->extends;
		}

		if( $this->implements ) {
			$res .= ' implements ' . implode( ', ', $this->implements );
		}

		$res .= $nl;
		$res .= '{' . $nl;

		foreach( $this->constants as $constant ) {
			$res .= $constant . $nl;
		}

		foreach( $this->properties as $property ) {
			$res .= $nl . $property . $nl;
		}

		foreach( $this->methods as $method ) {
			$res .= $nl . $method->toString( $ident, $nl ) . $nl;

		}
		$res .= '}' . $nl;

		if( $this->errors ) {
			return '';
		}


		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * @param string $error
	 */
	public function addError( string $error ): void
	{
		$this->errors[] = $error;
	}

	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getWarnings(): array
	{
		return $this->warnings;
	}

	/**
	 * @param string $warning
	 */
	public function addWarning( string $warning ): void
	{
		$this->warnings[] = $warning;
	}

}