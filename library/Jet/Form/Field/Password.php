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

class Form_Field_Password extends Form_Field_Abstract {
	const ERROR_CODE_CHECK_EMPTY = 'check_empty';
	const ERROR_CODE_CHECK_NOT_MATCH = 'check_not_match';
	const ERROR_CODE_WEAK_PASSWORD = 'weak_password';

	/**
	 * @var string
	 */
	protected $_type = Form::TYPE_PASSWORD;

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
	protected $disable_check = false;

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
	 * @return bool
	 */
	public function getDisableCheck() {
		return $this->disable_check;
	}

	/**
	 * @param bool $disable_check
	 */
	public function setDisableCheck($disable_check) {
		$this->disable_check = (bool)$disable_check;
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

		if( !$this->disable_check ) {
			$this->password_check_value = '';
			$name = $this->_name.'_check';

			if($data->exists($name)) {
				$this->password_check_value = trim( $data->getString($name ) );
			}
		}
		
		parent::catchValue($data);
	}

	/**
	 * @return bool
	 */
	public function checkValueIsNotEmpty() {
		if(!parent::checkValueIsNotEmpty()) {
			return false;
		}
				
		if( !$this->disable_check ) {
			if(
				$this->_value &&
				!$this->password_check_value
			) {
				$this->setValueError(self::ERROR_CODE_CHECK_EMPTY);
				return false;
			}
		}
		
		return true;
	}

	/**
	 * @return bool
	 */
	public function validateValue() {
		
		if(
			!$this->disable_check &&
			$this->_value
		) {
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
		}


		$this->_setValueIsValid();
		
		return true;
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field( Form_Parser_TagData $tag_data ) {

		$tag_data->setProperty( 'name', $this->getName() );
		$tag_data->setProperty( 'id', $this->getID() );
		$tag_data->setProperty( 'type', 'password' );
		$tag_data->setProperty( 'value', '' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';

	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field_check_label( Form_Parser_TagData $tag_data ) {
		if($this->disable_check) {
			return '';
		}

		$label = $this->getTranslation($this->password_check_label);

		if(
			$this->is_required &&
			$label
		) {
			$label = Data_Text::replaceData($this->__form->getTemplate_field_required(), ['LABEL'=>$label]);
		}

		$tag_data->setProperty('for', $this->getID().'_check' );


		return '<label '.$this->_getTagPropertiesAsString( $tag_data ).'>'.$label.'</label>';
	}

	/**
	 * @param Form_Parser_TagData $tag_data
	 *
	 * @return string
	 */
	protected function _getReplacement_field_check( Form_Parser_TagData $tag_data ) {
		if($this->disable_check) {
			return '';
		}

		$tag_data->setProperty( 'name', $this->getName().'_check' );
		$tag_data->setProperty( 'id', $this->getID().'_check' );
		$tag_data->setProperty( 'type', 'password' );
		$tag_data->setProperty( 'value', '' );

		return '<input '.$this->_getTagPropertiesAsString($tag_data).'/>';
	}

	/**
	 * @param string|null $template
	 *
	 * @return string
	 */
	public function helper_getBasicHTML($template=null) {
		if(!$template) {
			$template = $this->__form->getTemplate_field();
		}

		$result = Data_Text::replaceData($template, [
			'LABEL' => '<jet_form_field_label name="'.$this->_name.'"/>',
			'FIELD' => '<jet_form_field_error_msg name="'.$this->_name.'"/>'
					  .'<jet_form_field name="'.$this->_name.'" class="form-control"/>'
		]);

		if( !$this->disable_check ) {
			$result .= Data_Text::replaceData($template, [
				'LABEL' => '<jet_form_field_check_label name="'.$this->_name.'"/>',
				'FIELD' => '<jet_form_field_check name="'.$this->_name.'" class="form-control"/>'
			]);
		}

		return $result;

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

		if(!$this->disable_check) {
			$codes[] = self::ERROR_CODE_CHECK_EMPTY;
			$codes[] = self::ERROR_CODE_CHECK_NOT_MATCH;
		}

		if($this->password_strength_check_callback) {
			$codes[] = self::ERROR_CODE_WEAK_PASSWORD;
		}

		return $codes;
	}

}