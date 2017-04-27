<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_Checkbox
 * @package Jet
 */
class Form_Field_Checkbox extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_CHECKBOX;

	/**
	 * @var array
	 */
	protected $error_messages = [];


	/**
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value_raw = false;
		$this->_value = false;
		$this->_has_value = true;

		if($data->exists($this->_name)) {
			$this->_value_raw = $data->getRaw($this->_name);
			$this->_value = $data->getBool($this->_name);
		}

        $data->set($this->_name, $this->_value);
	}

	/**
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		return true;
	}


	/**
	 * @return bool
	 */
	public function validateValue() {
		$this->_setValueIsValid();
		
		return true;
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		return [];
	}
}