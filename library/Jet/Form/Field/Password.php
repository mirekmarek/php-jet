<?php 
/**
 *
 *
 *
 * class representing single form field - type string
 *
 *
 *
 *
 * specific options:
 * 		password_check_caption: 2nd (check) input box caption
 * 		disable_check
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
	/**
	 * @var string
	 */
	protected $_type = 'Password';

	/**
	 * @var array
	 */
	protected $error_messages = [
				'empty' => 'empty',
				'check_empty' => 'check_empty',
				'check_not_match' => 'check_not_match',
				'thin_password' => 'thin_password'
	];


	/**
	 * @var int
	 */
	protected $minimal_password_strength = 50;
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
	 * @return int
	 */
	public function getMinimalPasswordStrength() {
		return $this->minimal_password_strength;
	}

	/**
	 * @param int $minimal_password_strength
	 */
	public function setMinimalPasswordStrength( $minimal_password_strength ) {
		$this->minimal_password_strength = (int)$minimal_password_strength;
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
				$this->setValueError('check_empty');
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
				$this->setValueError('check_not_match');
				return false;
			}

			$password_strength = Auth::getPasswordStrength( $this->_value );

			if( $password_strength < $this->minimal_password_strength ) {
				$this->setValueError('thin_password');
				return false;
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
}