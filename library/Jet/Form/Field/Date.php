<?php 
/**
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_Date extends Form_Field_Abstract {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_DATE;

	/**
	 * @var array
	 */
	protected $error_messages = [
		self::ERROR_CODE_EMPTY => '',
		self::ERROR_CODE_INVALID_FORMAT => ''
	];


	/**
	 * validate value
	 *
	 * @return bool
	 */
	public function validateValue() {
		if(!$this->is_required && $this->_value==='') {
			return true;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if(!@strtotime($this->_value.' 00:00:00')) {
			$this->setValueError(self::ERROR_CODE_INVALID_FORMAT);
			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {
		parent::catchValue($data);

		if($this->_value) {
			$this->_value = date('Y-m-d',strtotime($this->_value));
		}
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];

		if($this->is_required ) {
			$codes[] = self::ERROR_CODE_EMPTY;
		}
		$codes[] = self::ERROR_CODE_INVALID_FORMAT;

		return $codes;
	}
}