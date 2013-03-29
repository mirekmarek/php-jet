<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Config
 * @subpackage Config_Definition
 */
namespace Jet;

class Config_Definition_Property_Float extends Config_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Config::TYPE_FLOAT;
	/**
	 * @var float
	 */
	protected $default_value = 0.0;
	/**
	 * @var string
	 */
	protected $form_field_type = "Float";

	/**
	 * @var float
	 */
	protected $min_value = NULL;

	/**
	 * @var float
	 */
	protected $max_value = NULL;


	/**
	 * @param float $min_value
	 */
	public function setMinValue($min_value) {
		$this->min_value = (float)$min_value;
	}

	/**
	 * @return float|null
	 */
	public function getMinValue() {
		return $this->min_value;
	}

	/**
	 * @param float $max_value
	 */
	public function setMaxValue($max_value) {
		$this->max_value = (float)$max_value;
	}

	/**
	 * @return float|null
	 */
	public function getMaxValue() {
		return $this->max_value;
	}



	/**
	 * @param mixed &$value
	 */
	public function checkValueType( &$value ) {
		$value = (float)$value;
	}

	/**
	 *
	 * @return Form_Field_Abstract
	 */
	public function getFormField() {
		/**
		 * @var Form_Field_Float $field
		 */
		$field = parent::getFormField();

		if($this->min_value!==null) {
			$field->setMinValue( $this->min_value );
		}


		if($this->max_value!==null) {
			$field->setMaxValue( $this->max_value );
		}

		return $field;
	}

	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = "Type: ".$this->getType()." ";

		$res .= ", required: ".($this->is_required ? "yes":"no");

		if($this->default_value) {
			$res .= ", default value: {$this->default_value}";
		}

		if($this->min_value) {
			$res .= ", min. value: {$this->min_value}";
		}

		if($this->max_value) {
			$res .= ", max. value: {$this->max_value}";
		}

		if($this->description) {
			$res .= "\n\n{$this->description}";
		}

		return $res;
	}

	/**
	 * Column value test - checks range
	 *
	 * @param mixed &$value
	 *
	 * @throws Config_Exception
	 * @return bool
	 */
	protected function _validateData_test_value( &$value ) {
		if($this->min_value===NULL && $this->max_value===NULL) {
			return true;
		}

		if(
			$this->min_value!==NULL &&
			$value<$this->min_value
		) {
			throw new Config_Exception(
				"Configuration property ".get_class($this->_configuration)."::".$this->_name." value is under the minimal value (by definition). Minimal value: {$this->min_value}, current value: {$value}",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		if(
			$this->max_value!==NULL &&
			$value>$this->max_value
		) {
			throw new Config_Exception(
				"Configuration property ".get_class($this->_configuration)."::".$this->_name." value is above the maximum value (by definition). Maximum value: {$this->max_value}, current value: {$value}",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);
		}

		return true;
	}

}