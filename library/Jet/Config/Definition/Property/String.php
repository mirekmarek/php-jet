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
	protected string $_type = Config::TYPE_STRING;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string|null
	 */
	protected string|null $validation_regexp = null;

	/**
	 * @var string|bool
	 */
	protected string|bool $form_field_type = Form::TYPE_INPUT;

	/**
	 * @param ?array $definition_data
	 *
	 * @throws Config_Exception
	 */
	public function setUp( ?array $definition_data = null ) : void
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
	protected function checkValueType( mixed &$value ) : void
	{
		$value = (string)$value;
	}

	/**
	 *
	 * @param mixed $value
	 *
	 * @throws Config_Exception
	 */
	protected function checkValue( mixed $value ) : void
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
	 * @return string|null
	 */
	public function getValidationRegexp() : string|null
	{
		return $this->validation_regexp;
	}

	/**
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp( string $validation_regexp ) : void
	{
		$this->validation_regexp = $validation_regexp;
		$this->form_field_validation_regexp = $validation_regexp;
	}


}