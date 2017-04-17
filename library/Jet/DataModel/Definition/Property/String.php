<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @return int|null
	 */
	public function getMaxLen() {
		return $this->max_len;
	}

	/**
	 * @return string
	 */
	public function getFormFieldType() {

		if($this->form_field_type!=Form::TYPE_INPUT) {
			return $this->form_field_type;
		}

		if($this->max_len<=255) {
			return Form::TYPE_INPUT;
		} else {
			return Form::TYPE_TEXTAREA;
		}
	}


	/**
	 * @return string
	 */
	public function getTechnicalDescription() {
		$res = 'Type: '.$this->getType().', max length: '.$this->max_len;

		$res .= ', required: '.($this->form_field_is_required ? 'yes':'no');

		if($this->is_id) {
			$res .= ', is ID';
		}

		if($this->default_value) {
			$res .= ', default value: '.$this->default_value;
		}

		if($this->form_field_validation_regexp) {

			$res .= ', validation regexp: '.$this->form_field_validation_regexp;
		}

		if($this->description) {
			$res .= JET_EOL.JET_EOL.$this->description;
		}

		return $res;
	}


}