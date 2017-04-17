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
 * @package Form
 */
namespace Jet;

class Form_Field_MultiSelect extends Form_Field_Abstract {
	const ERROR_CODE_INVALID_VALUE = 'invalid_value';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_MULTI_SELECT;

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => '',
				self::ERROR_CODE_INVALID_VALUE => ''
	];

	/**
	 * Validates values
	 *
	 * @return bool
	 */
	public function validateValue() {
		$options = $this->select_options;
		if(!$this->_value) {
			$this->_value = [];
		}

		if(!is_array($this->_value)) {
			$this->_value = [$this->_value];
		}

		foreach($this->_value as $item){
			if(!isset($options[$item])) {
				$this->setValueError(self::ERROR_CODE_INVALID_VALUE);
				return false;
			}
		}

		
		$this->_setValueIsValid();

		return true;
	}


	/**
	 * returns false if value is required and is empty
	 *
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		if(!$this->_value && $this->is_required) {
			$this->setValueError(self::ERROR_CODE_EMPTY );
			return false;
		}

		return true;
	}


	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		$this->_value = null;
		$this->_has_value = true;

		if( $data->exists($this->_name) ) {
			$this->_value_raw = $data->getRaw( $this->_name );

			if(is_array($this->_value_raw)){
				if(!empty($this->_value_raw)){
					$this->_value = [];
					foreach($this->_value_raw as $item){
						$this->_value[]=$item;
					}
				}			
			}else{
				$this->_value = [$this->_value_raw];
			}
		} else {
			$this->_value_raw = null;
			$this->_value = [];
		}
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		$codes[] = self::ERROR_CODE_INVALID_VALUE;

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}


		return $codes;
	}

}