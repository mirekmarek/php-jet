<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Reflection_ParserData
{

	/**
	 * @var array
	 */
	public $result_data = [];
	/**
	 * @var Reflection_Class
	 */
	protected $target_class_reflection;
	/**
	 * @var Reflection_Class[]
	 */
	protected $class_reflection_hierarchy = [];
	/**
	 * @var Reflection_Class
	 */
	protected $current_hierarchy_class_reflection;
	/**
	 * @var array
	 */
	protected $current_namespace_use_str = '';
	/**
	 * @var \ReflectionProperty|null
	 */
	protected $current_property_reflection;
	/**
	 * @var string
	 */
	protected $definition = '';
	/**
	 * @var string
	 */
	protected $reflection_parser_class_name = '';
	/**
	 * @var string
	 */
	protected $key = '';
	/**
	 * @var string
	 */
	protected $value_raw = '';
	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param string $class
	 */
	public function __construct( $class )
	{
		$this->target_class_reflection = new Reflection_Class( $class );

		$parent_class_reflection = $this->target_class_reflection;

		$class_reflection_hierarchy = [];

		while( $parent_class_reflection ) {

			foreach( $parent_class_reflection->getTraits() as $trait_reflection ) {
				array_unshift( $class_reflection_hierarchy, $trait_reflection );
			}

			array_unshift( $class_reflection_hierarchy, $parent_class_reflection );


			$parent_class_reflection = $parent_class_reflection->getParentClass();
		};

		$this->class_reflection_hierarchy = [];

		foreach( $class_reflection_hierarchy as $reflection ) {
			$this->class_reflection_hierarchy[$reflection->getName()] = new Reflection_Class(
				$reflection->getName()
			);
		}


	}

	/**
	 * @return Reflection_Class
	 */
	public function getTargetClassReflection()
	{
		return $this->target_class_reflection;
	}


	/**
	 * @return Reflection_Class[]
	 */
	public function getClassReflectionHierarchy()
	{
		return $this->class_reflection_hierarchy;
	}

	/**
	 * @return Reflection_Class
	 */
	public function getCurrentHierarchyClassReflection()
	{
		return $this->current_hierarchy_class_reflection;
	}

	/**
	 * @param Reflection_Class $current_hierarchy_class_reflection
	 */
	public function setCurrentHierarchyClassReflection( Reflection_Class $current_hierarchy_class_reflection )
	{
		$this->current_hierarchy_class_reflection = $current_hierarchy_class_reflection;
	}

	/**
	 * @param string $definition
	 * @param string $reflection_parser_class_name
	 * @param string $key
	 * @param string $value_raw
	 */
	public function setCurrentElement( $definition, $reflection_parser_class_name, $key, $value_raw )
	{

		$this->definition = $definition;
		$this->reflection_parser_class_name = $reflection_parser_class_name;
		$this->key = trim( $key );
		$this->value_raw = trim( $value_raw );
		$this->value = $this->parseValue();
	}

	/**
	 * @return mixed
	 *
	 * @throws Reflection_Exception
	 */
	protected function parseValue()
	{

		$value = null;

		$relevant_reflection = $this->getRelevantClassReflection();

		$eval_code = '';
		$eval_code .= 'namespace '.$relevant_reflection->getNamespaceName().';';
		$eval_code .= $relevant_reflection->getUseClassesStr();

		$eval_code .= '$value='.$this->value_raw.'; return true;';


		$error_message = '';
		try {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			$eval_res = @eval( $eval_code );
		} catch( \Exception $e ) {

			$error_message = $e->getMessage();
			$eval_res = false;
		}

		if( !$eval_res ) {
			if($error_message) {
				$error_message = JET_EOL.JET_EOL.$error_message;
			}

			throw new Reflection_Exception(
				'Value parse error! '
				.'Class:\''.$this->current_hierarchy_class_reflection->getName().'\', '
				.'Definition: \''.$this->definition.'\' '
				.$error_message
			);
		}

		return $value;

	}

	/**
	 * @return Reflection_Class
	 */
	protected function getRelevantClassReflection()
	{
		if( $this->current_property_reflection ) {
			$declaring_class_name = $this->current_property_reflection->getDeclaringClass()->getName();

			$relevant_class_reflection = $this->class_reflection_hierarchy[$declaring_class_name];
		} else {
			$relevant_class_reflection = $this->current_hierarchy_class_reflection;
		}

		return $relevant_class_reflection;
	}

	/**
	 * @return string
	 */
	public function getDefinition()
	{
		return $this->definition;
	}

	/**
	 * @return string
	 */
	public function getReflectionParserClassName()
	{
		return $this->reflection_parser_class_name;
	}

	/**
	 * @return string
	 */
	public function getValueRaw()
	{
		return $this->value_raw;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function getValueAsBool()
	{
		$value = strtolower( $this->value_raw );

		$value = ( $value=='true' );

		return (bool)$value;
	}

	/**
	 * @return int
	 */
	public function getValueAsInt()
	{
		return (int)$this->value_raw;
	}

	/**
	 * @return string
	 *
	 * @throws Reflection_Exception
	 */
	public function getValueAsString()
	{
		return (string)$this->value;
	}

	/**
	 * @return array
	 *
	 * @throws Reflection_Exception
	 */
	public function getValueAsArray()
	{

		if( !is_array( $this->value ) ) {
			throw new Reflection_Exception(
				'Value parse error! '.JET_EOL.'Class:\''.$this->getRelevantClassReflection()->getName(
				).'\''.JET_EOL.'Definition: \''.$this->definition.'\''.JET_EOL.'Error: value is not array'
			);
		}

		return $this->value;
	}

	/**
	 * @return string
	 * @throws Reflection_Exception
	 */
	public function getValueAsClassName()
	{
		return $this->getRealClassName( $this->value );
	}

	/**
	 * @param string $class_name
	 *
	 * @return string
	 * @throws Reflection_Exception
	 */
	public function getRealClassName( $class_name )
	{
		if( strpos( $class_name, '\\' ) ) {
			return $class_name;
		}

		$class_reflection = $this->getRelevantClassReflection();

		if( $class_name=='this' ) {
			return $class_reflection->getName();
		}

		$class_map = $class_reflection->getUseClassesMap();

		if( !isset( $class_map[$class_name] ) ) {
			return $class_reflection->getNamespaceName().'\\'.$class_name;
		}

		return $class_map[$class_name];

	}

	/**
	 * @return callable
	 *
	 * @throws Reflection_Exception
	 */
	public function getValueAsCallback()
	{

		$callback = $this->value;

		if( is_array( $callback ) ) {
			$callback[0] = $this->getRealClassName( $callback[0] );
		}

		return $callback;
	}

	/**
	 * @param string      $section
	 * @param mixed       $value
	 * @param string|null $property_name
	 * @param string|null $key
	 */
	public function setResultDataPropertyValue( $section, $value, $property_name = null, $key = null )
	{

		if( !$property_name ) {
			$property_name = $this->getCurrentPropertyReflection()->getName();
		}
		if( !$key ) {
			$key = $this->getKey();
		}

		if( !isset( $this->result_data[$section] ) ) {
			$this->result_data[$section] = [];
		}
		if( !isset( $this->result_data[$section][$property_name] ) ) {
			$this->result_data[$section][$property_name] = [];
		}

		$this->result_data[$section][$property_name][$key] = $value;
	}

	/**
	 * @return null|\ReflectionProperty
	 */
	public function getCurrentPropertyReflection()
	{
		return $this->current_property_reflection;
	}

	/**
	 * @param null|\ReflectionProperty $current_property_reflection
	 */
	public function setCurrentPropertyReflection( \ReflectionProperty $current_property_reflection = null )
	{
		$this->current_property_reflection = $current_property_reflection;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

}