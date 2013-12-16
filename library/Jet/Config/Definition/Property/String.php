<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_String extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_STRING;

	/**
	 * @var string
	 */
	protected $default_value = "";

	/**
	 * @var string
	 */
	protected $form_field_type = "Input";

	/**
	 * @var string
	 */
	protected $validation_regexp = null;

	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value ) {
		$value = (string)$value;
	}

	/**
	 * @return string
	 */
	public function getValidationRegexp() {
		return $this->validation_regexp;
	}

	/**
	 * @param string $validation_regexp
	 */
	public function setValidationRegexp($validation_regexp) {
		$this->validation_regexp = $validation_regexp;
	}

	/**
	 *
	 * @return Form_Field_Abstract
	 */
	public function getFormField() {
		$field = parent::getFormField();

		if($this->validation_regexp) {
			$field->setValidationRegexp( $this->validation_regexp );
		}

		return $field;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = "Type: ".$this->getType();

		$res .= ", required: ".($this->is_required ? "yes":"no");

		if($this->default_value) {
			$res .= ", default value: {$this->default_value}";
		}

		if($this->validation_regexp) {
			$res .= ", valid value regular expression: {$this->validation_regexp}";
		}

		if($this->description) {
			$res .= "\n\n{$this->description}";
		}

		return $res;
	}


	/**
	 * Column value test - checks format
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateProperties_test_value( &$value ) {
		if(!$this->validation_regexp) {
			return true;
		}

		if( !preg_match($this->validation_regexp, $value) ) {
			throw new Config_Exception(
				"Configuration property ".get_class($this->_configuration)."::".$this->_name." has invalid format. Valid regexp: {$this->validation_regexp}, current value: {$value}",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}
}