<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Config_Definition_Property_String extends Config_Definition_Property
{
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_STRING;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $validation_regexp = null;

	/**
	 * @param array|null $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( array $definition_data = null )
	{
		parent::setUp( $definition_data );

		if( $this->validation_regexp!==null ) {
			$this->form_field_validation_regexp = $this->validation_regexp;
		}

		if( $this->form_field_type===null ) {
			$this->form_field_type = Form::TYPE_INPUT;
		}

	}

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value )
	{
		$value = (string)$value;
	}

	/**
	 *
	 * @param mixed $value
	 *
	 * @throws Config_Exception
	 */
	protected function checkValue( $value )
	{
		if(
			$this->validation_regexp &&
			!preg_match( $this->validation_regexp, $value )
		) {
			throw new Config_Exception(
				'Configuration property '.$this->_configuration_class.'::'.$this->name.' has invalid format. Valid regexp: '.$this->validation_regexp.', current value: '.$value,
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

	}

	/**
	 * @return string
	 */
	public function getValidationRegexp()
	{
		return $this->validation_regexp;
	}

	/**
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( $validation_regexp )
	{
		$this->validation_regexp = $validation_regexp;
		$this->form_field_validation_regexp = $validation_regexp;
	}


}