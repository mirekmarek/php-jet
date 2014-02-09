<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Config extends Object {
	/**
	 * Property definition classes names prefix
	 */
	const BASE_PROPERTY_DEFINITION_CLASS_NAME = 'Jet\\Config_Definition_Property';

	/**
	 * @var string
	 */
	protected $class_name = '';


	/**
	 * @var string
	 */
	protected $data_path = '';

	/**
	 * @var bool
	 */
	protected $section_is_obligatory = true;

	/**
	 * @var Config_Definition_Property_Abstract[]
	 */
	protected $properties_definition = array();

	/**
	 * @param string $class_name
	 *
	 * @throws Config_Exception
	 */
	public function __construct( $class_name='' ) {
		if(!$class_name) {
			return;
		}

		$this->class_name = $class_name;

		$this->data_path = Object_Reflection::get( $class_name, 'config_data_path', '' );
		$this->section_is_obligatory = Object_Reflection::get( $class_name, 'config_section_is_obligatory', true );

		$propertied_definition_data = Object_Reflection::get( $class_name, 'config_properties_definition', array() );

		$this->properties_definition = array();
		foreach( $propertied_definition_data as $property_name=>$definition_data ) {
			if(
				!isset($definition_data['type']) ||
				!$definition_data['type']
			) {
				throw new Config_Exception(
					'Property '.get_class($this).'::'.$property_name.': \'type\' parameter is not defined.',
					Config_Exception::CODE_CONFIG_CHECK_ERROR
				);

			}

			$class_name = static::BASE_PROPERTY_DEFINITION_CLASS_NAME.'_'.$definition_data['type'];

			unset($definition_data['type']);

			$property = new $class_name( $class_name, $property_name, $definition_data );

			//Factory::checkInstance(static::BASE_PROPERTY_DEFINITION_CLASS_NAME.'_Abstract', $property);

			$this->properties_definition[$property_name] = $property;

		}


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
	public function getDataPath() {
		return $this->data_path;
	}

	/**
	 * @return Config_Definition_Property_Abstract[]
	 */
	public function getPropertiesDefinition() {
		return $this->properties_definition;
	}

	/**
	 * @return boolean
	 */
	public function getSectionIsObligatory() {
		return $this->section_is_obligatory;
	}

	/**
	 * @param string $class_name
	 *
	 * @return Config_Definition_Config
	 */
	public static function getDefinition( $class_name ) {

		$file_path = JET_CONFIG_DEFINITION_CACHE_PATH.str_replace('\\', '__', $class_name.'.php');

		if( JET_CONFIG_DEFINITION_CACHE_LOAD ) {

			if(IO_File::isReadable($file_path)) {
				/** @noinspection PhpIncludeInspection */
				$definition = require $file_path;

				return $definition;
			}
		}

		$definition = new static( $class_name );

		if(JET_CONFIG_DEFINITION_CACHE_SAVE) {
			try {
				IO_File::write( $file_path, '<?php return '.@var_export($definition, true).';' );
			} catch(Exception $e) {}
		}


		return $definition;
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
			case 'section_is_obligatory':
				$reflection_data['config_section_is_obligatory'] = (bool)$value;
				break;
			case 'data_path':
				$reflection_data['config_data_path'] = (string)$value;
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
		&$reflection_data,
		$property_name,
		$key,
		/** @noinspection PhpUnusedParameterInspection */
		$definition,
		$value
	) {
		if(!isset($reflection_data['config_properties_definition'])) {
			$reflection_data['config_properties_definition'] = array();
		}
		if(!isset($reflection_data['config_properties_definition'][$property_name])) {
			$reflection_data['config_properties_definition'][$property_name] = array();
		}

		$reflection_data['config_properties_definition'][$property_name][$key] = $value;

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