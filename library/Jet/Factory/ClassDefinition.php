<?php
/**
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Factory
 */
namespace Jet;

class Factory_ClassDefinition extends Object {

	/**
	 * @var string
	 */
	protected $class_name = '';

	/**
	 * @var string
	 */
	protected $factory_class = '';

	/**
	 * @var string
	 */
	protected $factory_method = '';

	/**
	 * @var string
	 */
	protected $factory_mandatory_parent_class = '';

	/**
	 * @param string $class_name (optional)
	 *
	 */
	public function __construct( $class_name='' ) {
		if(!$class_name) {
			return;
		}

		$this->class_name = $class_name;

		$this->factory_class = Object_Reflection::get( $class_name, 'factory_class', null );
		$this->factory_method = Object_Reflection::get( $class_name, 'factory_method', null );
		$this->factory_mandatory_parent_class = Object_Reflection::get( $class_name, 'factory_mandatory_parent_class', null );

	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->class_name;
	}

	/**
	 * @return string
	 */
	public function getFactoryClass() {
		return $this->factory_class;
	}

	/**
	 * @return string
	 */
	public function getFactoryMethod() {
		return $this->factory_method;
	}

	/**
	 * @return string
	 */
	public function getFactoryMandatoryParentClass() {
		return $this->factory_mandatory_parent_class;
	}

	/**
	 * @param &$reflection_data
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment( &$reflection_data, $key, $definition, $value ) {

		switch($key) {
			case 'class':
				/*if(
					!isset($reflection_data['factory_class']) ||
					!$reflection_data['factory_class']
				) */ {
					$reflection_data['factory_class'] = (string)$value;
				}
				break;
			case 'method':
				/*if(
					!isset($reflection_data['factory_method']) ||
					!$reflection_data['factory_method']
				) */ {
					$reflection_data['factory_method'] = (string)$value;
				}
				break;
			case 'mandatory_parent_class':
				/*if(
					!isset($reflection_data['factory_mandatory_parent_class']) ||
					!$reflection_data['factory_mandatory_parent_class']
				) */ {
					$reflection_data['factory_mandatory_parent_class'] = (string)$value;
				}
				break;
			default:
				throw new Object_Reflection_Exception(
					'Unknown definition! Class: \''.get_called_class().'\', definition: \''.$definition.'\' ',
					Object_Reflection_Exception::CODE_UNKNOWN_CLASS_DEFINITION
				);
		}

	}

	/**
	 * @param array &$reflection_data
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parsePropertyDocComment(
		/** @noinspection PhpUnusedParameterInspection */
		&$reflection_data,
		$property_name,
		/** @noinspection PhpUnusedParameterInspection */
		$key,
		$definition,
		/** @noinspection PhpUnusedParameterInspection */
		$value
	) {
		throw new Object_Reflection_Exception(
			'Unknown definition! Class: \''.get_called_class().'\', property: \''.$property_name.'\', definition: \''.$definition.'\' ',
			Object_Reflection_Exception::CODE_UNKNOWN_PROPERTY_DEFINITION
		);
	}

	/**
	 * @param $data
	 *
	 * @return static
	 */
	public static function __set_state( $data ) {
		$i = new static();

		foreach( $data as $key=>$val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

}
