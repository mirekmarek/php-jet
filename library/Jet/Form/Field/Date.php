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
	protected $_type = 'Date';

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
			$this->setValueError('invalid_format');
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
}