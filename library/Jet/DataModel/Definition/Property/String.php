<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Definition
 */
namespace Jet;

class DataModel_Definition_Property_String extends DataModel_Definition_Property_Abstract {
	/**
	 * @var string
	 */
	protected $_type = DataModel::TYPE_STRING;

	/**
	 * @var null|string
	 */
	protected $validation_regexp = null;

	/**
	 * @var int
	 */
	protected $max_len = 255;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var string
	 */
	protected $form_field_type = Form::TYPE_INPUT;

	/**
	 * @param $definition_data
	 */
	public function setUp( $definition_data ) {
		if($definition_data) {
			parent::setUp($definition_data);

			$this->max_len = (int)$this->max_len;
		}

	}

	/**
	 * @param mixed &$value
	 *
	 */
	public function checkValueType( &$value ) {
		$value = (string)$value;
	}

	/**
	 * @return string|null
	 */
	public function getValidationRegexp() {
		return $this->validation_regexp;
	}

	/**
	 * @return int|null
	 */
	public function getMaxLen() {
		return $this->max_len;
	}


	/**
	 * Column value test - checks format
	 *
	 * @param mixed &$property
	 * @param DataModel_Validation_Error &$errors
	 * @param string $locale_str
	 *
	 * @return bool
	 */
	public function _validatePropertyValue_test_value( &$property, &$errors, $locale_str=null ) {

		if(!$this->validation_regexp) {
			return true;
		}
		
		if(!preg_match($this->validation_regexp, $property)) {
			$errors[] = new DataModel_Validation_Error(
					DataModel_Validation_Error::CODE_INVALID_FORMAT,
					$this, $property, $locale_str
				);

			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType() {

		if($this->form_field_type!='Input') {
			return $this->form_field_type;
		}

		if($this->max_len<=255) {
			return 'Input';
		} else {
			return 'Textarea';
		}
	}

	/**
	 * @return array|Form_Field_Abstract
	 */
	public function getFormField() {
		$field = parent::getFormField();
		if($this->validation_regexp) {
			$field->setValidationRegexp($this->validation_regexp);
		}
		return $field;
	}


	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType().', max length: '.$this->max_len;

		$res .= ', required: '.($this->is_required ? 'yes':'no');

		if($this->is_ID) {
			$res .= ', is ID';
		}

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->validation_regexp) {

			$res .= ', validation regexp: '.$this->validation_regexp;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}


}