<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio;

use Jet\BaseObject;
use Jet\BaseObject_Exception;
use Jet\Exception;
use Jet\IO_File;

class ClassCreator_Class extends BaseObject {
	const VISIBILITY_PUBLIC = 'public';
	const VISIBILITY_PROTECTED = 'protected';
	const VISIBILITY_PRIVATE = 'private';

	protected static $indentation = "\t";
	protected static $nl = "\n";

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var ClassCreator_UseClass[]
	 */
	protected $use = [];

	/**
	 * @var ClassCreator_Annotation[]
	 */
	protected $annotations = [];

	/**
	 * @var bool
	 */
	protected $is_abstract = false;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $extends = '';

	/**
	 * @var array
	 */
	protected $implements = [];

	/**
	 * @var ClassCreator_Class_Constant[]
	 */
	protected $constants = [];

	/**
	 * @var ClassCreator_Class_Property[]
	 */
	protected $properties = [];

	/**
	 * @var ClassCreator_Class_Method[]
	 */
	protected $methods = [];

	/**
	 * @var array
	 */
	protected $errors = [];

	/**
	 * @var array
	 */
	protected $warnings = [];

	/**
	 * @var ClassCreator_ActualizeDecisionMaker
	 */
	protected $actualize_decision_maker;

	/**
	 * @return bool
	 */
	public function isAbstract()
	{
		return $this->is_abstract;
	}

	/**
	 * @param bool $is_abstract
	 */
	public function setIsAbstract( $is_abstract )
	{
		$this->is_abstract = $is_abstract;
	}

	/**
	 * @return ClassCreator_UseClass[]
	 */
	public function getUse()
	{
		return $this->use;
	}

	/**
	 * @return ClassCreator_Annotation[]
	 */
	public function getAnnotations()
	{
		return $this->annotations;
	}

	/**
	 * @return ClassCreator_Class_Constant[]
	 */
	public function getConstants()
	{
		return $this->constants;
	}

	/**
	 * @return ClassCreator_Class_Property[]
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * @return ClassCreator_Class_Method[]
	 */
	public function getMethods()
	{
		return $this->methods;
	}

	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method|null
	 */
	public function getMethod( $name )
	{
		if(!isset($this->methods[$name])) {
			return null;
		}

		return $this->methods[$name];
	}


	/**
	 * @return string
	 */
	public static function getIndentation()
	{
		return static::$indentation;
	}

	/**
	 * @param string $indentation
	 */
	public static function setIndentation($indentation)
	{
		static::$indentation = $indentation;
	}

	/**
	 * @return string
	 */
	public static function getNl()
	{
		return static::$nl;
	}

	/**
	 * @param string $nl
	 */
	public static function setNl($nl)
	{
		static::$nl = $nl;
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
		$this->namespace = $namespace;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFullName()
	{
		return $this->getNamespace().'\\'.$this->getName();
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getExtends()
	{
		return $this->extends;
	}

	/**
	 * @param string $extends
	 */
	public function setExtends($extends)
	{
		$this->extends = $extends;
	}

	/**
	 * @param string $class_name
	 */
	public function addImplements( $class_name )
	{
		$this->implements[] = $class_name;
	}

	/**
	 * @param ClassCreator_UseClass $use_class
	 */
	public function addUse( ClassCreator_UseClass $use_class )
	{
		foreach( $this->use as $e_use  ) {
			if(
				$e_use->getClass()==$use_class->getClass()
			) {
				if($e_use->getUseAs()!=$use_class->getUseAs()) {
					throw new BaseObject_Exception('Use class collision: '.$e_use->getClass().' as '.$e_use.' VS '.$use_class->getClass().' as '.$use_class->getUseAs());
				}

				return;
			}
		}

		$this->use[] = $use_class;
	}

	/**
	 * @param ClassCreator_Annotation $annotation
	 */
	public function addAnnotation( ClassCreator_Annotation $annotation )
	{
		$this->annotations[] = $annotation;
	}



	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return ClassCreator_Class_Constant
	 */
	public function createConstant( $name, $value )
	{
		$constant = new ClassCreator_Class_Constant( $name, $value );

		$this->addConstant( $constant );

		return $constant;
	}

	/**
	 * @param ClassCreator_Class_Constant $constant
	 */
	public function addConstant( ClassCreator_Class_Constant $constant )
	{
		if(isset( $this->constants[$constant->getName()] )) {
			throw new BaseObject_Exception('Constant '.$constant->getName().' already defined');
		}

		$this->constants[$constant->getName()] = $constant;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasConstant( $name )
	{
		return isset( $this->constants[$name] );
	}



	/**
	 * @param string $name
	 * @param string $type
	 *
	 * @return ClassCreator_Class_Property
	 */
	public function createProperty( $name, $type )
	{
		$property = new ClassCreator_Class_Property( $name, $type );

		$this->addProperty( $property );

		return $property;
	}

	/**
	 * @param ClassCreator_Class_Property $property
	 */
	public function addProperty( ClassCreator_Class_Property $property )
	{
		if(isset( $this->properties[$property->getName()] )) {
			throw new BaseObject_Exception('Property '.$property->getName().' already defined');
		}

		$this->properties[$property->getName()] = $property;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasProperty( $name )
	{
		return isset( $this->properties[$name] );
	}

	/**
	 * @param string $name
	 *
	 * @return ClassCreator_Class_Method
	 */
	public function createMethod( $name )
	{
		$method = new ClassCreator_Class_Method( $name );

		$this->addMethod( $method );

		return $method;
	}

	/**
	 * @param ClassCreator_Class_Method $method
	 */
	public function addMethod( ClassCreator_Class_Method $method )
	{
		if(isset( $this->methods[$method->getName()] )) {
			$this->addError('Method '.$method->getName().' already defined');
		}

		$this->methods[$method->getName()] = $method;

	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasMethod( $name )
	{
		return isset( $this->methods[$name] );
	}

	/**
	 * @return string
	 */
	public function generateClassAnnotation()
	{
		$nl = ClassCreator_Class::getNl();

		$res = '';

		$res .= '/**'.$nl;
		$res .= ' *'.$nl;
		foreach( $this->annotations as $annotation) {
			$res .= ' * '.$annotation.$nl;
		}
		$res .= ' */'.$nl;

		return $res;
	}

	/**
	 * @return string
	 */
	public function toString()
	{

		$res = '';

		$use_str = [];
		foreach( $this->use as $use  ) {
			$use_str[] = $use->toString();
		}

		asort( $use_str );


		$ident = ClassCreator_Class::getIndentation();
		$nl = ClassCreator_Class::getNl();


		$res .= '/**'.$nl;
		$res .= ' * '.$nl;
		//TODO: $res .= ' * @author: '.Projects::getCurrentProject()->getAuthor().$nl;
		//TODO: $res .= ' * @license: '.Projects::getCurrentProject()->getLicense().$nl;
		$res .= ' */'.$nl;

		$res .= $nl;
		$res .= 'namespace '.$this->getNamespace().';'.$nl;

		$res .= $nl;
		$res .= implode($nl, $use_str).$nl;
		$res .= $nl;

		$res .= $this->generateClassAnnotation();

		$res .= ($this->isAbstract() ? 'abstract ':'').'class '.$this->name;
		if($this->extends) {
			$res .= ' extends '.$this->extends;
		}

		if($this->implements) {
			$res .= ' implements '.implode(', ',$this->implements);
		}

		$res .= $nl;
		$res .= '{'.$nl;

		foreach( $this->constants as $constant ) {
			$res .= $constant.$nl;
		}

		foreach( $this->properties as $property ) {
			$res .= $property.$nl;
		}

		foreach( $this->methods as $method ) {
			$res .= $method->toString( $ident, $nl ).$nl;

		}
		$res .= '}'.$nl;

		if( $this->errors ) {
			return '';
		}


		return $res;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @param $error
	 */
	public function addError( $error )
	{
		$this->errors[] = $error;
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return array
	 */
	public function getWarnings()
	{
		return $this->warnings;
	}

	/**
	 * @param $warning
	 */
	public function addWarning( $warning )
	{
		$this->warnings[] = $warning;
	}

	/**
	 * @return ClassCreator_ActualizeDecisionMaker
	 */
	public function getActualizeDecisionMaker()
	{
		return $this->actualize_decision_maker;
	}

	/**
	 * @param ClassCreator_ActualizeDecisionMaker $actualize_decision_maker
	 */
	public function setActualizeDecisionMaker( ClassCreator_ActualizeDecisionMaker $actualize_decision_maker )
	{
		$this->actualize_decision_maker = $actualize_decision_maker;
	}



	/**
	 * @param string $path
	 * 
	 */
	public function write( $path )
	{

		if(IO_File::exists($path)) {
			$this->actualize( $path );
		} else {
			IO_File::write( $path, '<?php '.JET_EOL.$this->toString() );
		}

	}

	/**
	 * @param string $path
	 */
	public function backup( $path )
	{
		$dir = dirname( $path );
		$file_name = 'backup_'.date('YmdHis').'_'.basename($path);

		IO_File::copy( $path, $dir.'/'.$file_name );
	}

	/**
	 * @param $path
	 */
	public function actualize( $path )
	{
		$old_script = IO_File::read( $path );

		$parser = new ClassParser( $old_script );

		$parser->actualizeClass( $this );

		$new_script = $parser->toString();

		if($old_script!=$new_script) {
			$this->backup( $path );
			IO_File::write( $path, $new_script );
		}
	}

}