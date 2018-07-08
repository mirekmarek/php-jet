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
			if( !$this->objectHasProperty( $key ) ) {
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
	public function getType()
	{
		return $this->_type;
	}

	/**
	 *
	 * @param mixed $value
	 * @param Config $config
	 *
	 * @return mixed
	 *
	 * @throws Config_Exception
	 */
	public function prepareValue( $value, Config $config )
	{

		$this->checkValueType( $value );
		$this->checkValue( $value );

		return $value;

	}

	/**
	 *
	 * @param mixed &$value
	 */
	abstract protected function checkValueType( &$value );

	/**
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 */
	abstract protected function checkValue( $value );

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

}