<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Config_Definition_Property_Abstract
 * @package Jet
 */
abstract class Config_Definition_Property extends BaseObject implements Form_Field_Definition_Interface
{
	use Form_Field_Definition_Trait;

	/**
	 * @var string
	 */
	protected $_type;

	/**
	 * @var Config
	 */
	protected $_configuration;

	/**
	 * @var string
	 */
	protected $_configuration_class;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var mixed
	 */
	protected $default_value = '';

	/**
	 * @var bool
	 */
	protected $is_required = false;

	/**
	 * @var string
	 */
	protected $error_message = '';


	/**
	 *
	 * @param string|Config $configuration_class_name
	 * @param string        $name
	 * @param array         $definition_data (optional)
	 *
	 */
	public function __construct( $configuration_class_name, $name, array $definition_data = null )
	{
		if( is_object( $configuration_class_name ) ) {
			/**
			 * @var Config $configuration_class_name
			 */
			$this->setConfiguration( $configuration_class_name );
		} else {
			$this->_configuration_class = $configuration_class_name;
		}


		$this->name = $name;

		$this->setUp( $definition_data );
	}

	/**
	 * @param Config $configuration
	 */
	public function setConfiguration( Config $configuration )
	{
		$this->_configuration = $configuration;
		$this->_configuration_class = get_class( $configuration );
	}

	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		if( !$definition_data ) {
			return;
		}

		foreach( $definition_data as $key => $val ) {
			if( !$this->getObjectClassHasProperty( $key ) ) {
				throw new Config_Exception(
					$this->_configuration_class.'::'.$this->name.': unknown definition option \''.$key.'\'  ',
					Config_Exception::CODE_DEFINITION_NONSENSE
				);
			}

			$this->{$key} = $val;
		}


		$this->is_required = (bool)$this->is_required;
		if( $this->is_required ) {
			$this->form_field_is_required = true;
		}
	}

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function __set_state( array $data )
	{
		$i = new static( $data['_configuration_class'], $data['name'] );

		foreach( $data as $key => $val ) {
			$i->{$key} = $val;
		}

		return $i;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->_configuration_class.'::'.$this->getName();
	}

	/**
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description )
	{
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->default_value;
	}

	/**
	 * @param mixed $default_value
	 */
	public function setDefaultValue( $default_value )
	{
		$this->default_value = $default_value;
	}

	/**
	 * @return bool
	 */
	public function getIsRequired()
	{
		return $this->is_required;
	}

	/**
	 * @param bool $is_required
	 */
	public function setIsRequired( $is_required )
	{
		$this->is_required = $is_required;
		$this->form_field_is_required = $is_required;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage()
	{
		return $this->error_message;
	}

	/**
	 * @param string $error_message
	 */
	public function setErrorMessage( $error_message )
	{
		$this->error_message = $error_message;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription()
	{
		$res = 'Type: '.$this->getType();

		$res .= ', required: '.( $this->is_required ? 'yes' : 'no' );

		if( $this->default_value ) {
			$res .= ', default value: '.$this->default_value;
		}

		if( $this->description ) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Check column (data) by definition (retype)
	 *  - type
	 *
	 * @param mixed &$value
	 *
	 * @return bool
	 * @throws Config_Exception
	 */
	public function checkValue( &$value )
	{

		$this->checkValueType( $value );

		if( $this->_validateProperties_test_required( $value ) ) {
			return $this->_validateProperties_test_value( $value );
		}

		return true;
	}

	/**
	 * Check data type by definition (retype)
	 *
	 * @param mixed &$value
	 */
	abstract function checkValueType( &$value );

	/**
	 * Property required test
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateProperties_test_required( &$value )
	{
		if( !$this->is_required ) {
			return true;
		}

		if( !$value ) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' is required by definition, but value is missing!',
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}

	/**
	 * Property value test - can be specific for each column type (eg: min and max value for number, string format ...)
	 *
	 * @param mixed &$value
	 *
	 * @return bool
	 */
	protected function _validateProperties_test_value( /** @noinspection PhpUnusedParameterInspection */
		&$value )
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getFormFieldName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextClassName()
	{
		return $this->_configuration_class;
	}

	/**
	 * @return string
	 */
	public function getFormFieldContextPropertyName()
	{
		return $this->name;
	}

}