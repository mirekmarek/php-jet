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
 * @package Form
 */
namespace Jet;

class Form_Field_DateTime extends Form_Field_Input {
	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_DATE_TIME;

	/**
	 * @var string
	 */
	protected $_input_type = 'datetime-local';

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
			$this->_value = null;

			return true;
		}


		$check = \DateTime::createFromFormat('Y-m-d\TH:i', $this->_value);

		if(!$check) {
			$this->setValueError(self::ERROR_CODE_INVALID_FORMAT);
			return false;
		}

		$this->_setValueIsValid();

		return true;
	}

	/**
	 * returns field value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->_value;
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

	/**
	 * @param Form_Parser_TagData $tag_data
	 */
	protected function _getReplacement_field_prepareParams( Form_Parser_TagData $tag_data )
	{
		parent::_getReplacement_field_prepareParams($tag_data);

		$value = '';
		if($this->_value) {
			$date = new \DateTime( $this->_value );

			if($date) {
				$value = $date->format('Y-m-d\TH:i');
			}
		}

		$tag_data->setProperty( 'value', $value );

	}
}