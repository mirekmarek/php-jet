<?php 
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Form
 */
namespace Jet;

class Form_Field_RegistrationPassword extends Form_Field_Abstract {
	const ERROR_CODE_CHECK_EMPTY = 'check_empty';
	const ERROR_CODE_CHECK_NOT_MATCH = 'check_not_match';
	const ERROR_CODE_WEAK_PASSWORD = 'weak_password';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_REGISTRATION_PASSWORD;

	/**
	 * @var array
	 */
	protected $error_messages = [
				self::ERROR_CODE_EMPTY => '',
				self::ERROR_CODE_CHECK_EMPTY => '',
				self::ERROR_CODE_CHECK_NOT_MATCH => '',
				self::ERROR_CODE_WEAK_PASSWORD => ''
	];

	/**
	 * @var bool
	 */
	protected $is_required = true;

	/**
	 * @var string
	 */
	protected $password_check_value = '';

	/**
	 * @var string
	 */
	protected $password_check_label = '';

	/**
	 * @var callable
	 */
	protected $password_strength_check_callback;

	/**
	 * @param callable $password_strength_check_callback
	 */
	public function setPasswordStrengthCheckCallback($password_strength_check_callback)
	{
		$this->password_strength_check_callback = $password_strength_check_callback;
	}

	/**
	 * @return callable
	 */
	public function getPasswordStrengthCheckCallback()
	{
		return $this->password_strength_check_callback;
	}


	/**
	 * @return string
	 */
	public function getPasswordCheckLabel() {
		return $this->password_check_label;
	}

	/**
	 * @param string $password_check_label
	 */
	public function setPasswordCheckLabel($password_check_label) {
		$this->password_check_label = $password_check_label;
	}



	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {

		$this->password_check_value = '';
		$name = $this->_name.'_check';

		if($data->exists($name)) {
			$this->password_check_value = trim( $data->getString($name ) );
		}
		
		parent::catchValue($data);
	}

	/**
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		if(!$this->_value) {
			$this->setValueError(self::ERROR_CODE_EMPTY);

			return false;
		}

		if(!$this->password_check_value) {
			$this->setValueError(self::ERROR_CODE_CHECK_EMPTY);

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function validateValue() {


		if( $this->_value!=$this->password_check_value ) {
			$this->setValueError(self::ERROR_CODE_CHECK_NOT_MATCH);
			return false;
		}

		$check_callback = $this->getPasswordStrengthCheckCallback();

		if($check_callback) {
			if( !$check_callback($this->_value) ) {
				$this->setValueError(self::ERROR_CODE_WEAK_PASSWORD);
				return false;
			}
		}



		$this->_setValueIsValid();
		
		return true;
	}

	protected function _getReplacement_field_label( Form_Parser_TagData $tag_data ) {
		if($this->getIsReadonly()) {
			return '';
		}

		return parent::_getReplacement_field_label( $tag_data );
	}

	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		if($this->getIsReadonly()) {
			return '';
		}

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'password' );
		$tag_data->setProperty( 'value', '' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';

	}

	protected function _getReplacement_field_check_label( Form_Parser_TagData $tag_data ) {
		if($this->getIsReadonly()) {
			return '';
		}

		$label = $this->getTranslation($this->password_check_label);

		if(
			$this->is_required &&
			$label
		) {
			$label = Data_Text::replaceData($this->_form->getTemplate_field_required(), ['LABEL'=>$label]);
		}

		$tag_data->setProperty('for', $this->getID().'_check' );


		return '<label '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$label.'</label>';
	}

	protected function _getReplacement_field_check( Form_Parser_TagData $tag_data ) {
		if($this->getIsReadonly()) {
			return '';
		}

		$tag_data->setProperty( 'name', $this->getName().'_check' );
		$tag_data->setProperty( 'id', $this->getID().'_check' );
		$tag_data->setProperty( 'type', 'password' );
		$tag_data->setProperty( 'value', '' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}


	/**
	 * @return array
	 */
	public function getRequiredErrorCodes()
	{
		$codes = [];


		$codes[] = self::ERROR_CODE_EMPTY;
		$codes[] = self::ERROR_CODE_CHECK_EMPTY;
		$codes[] = self::ERROR_CODE_CHECK_NOT_MATCH;


		if($this->password_strength_check_callback) {
			$codes[] = self::ERROR_CODE_WEAK_PASSWORD;
		}

		return $codes;
	}

	public function __toString()
	{
		//TODO:
		return '';
	}
}