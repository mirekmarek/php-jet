<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Form_Field_RegistrationPassword
 * @package Jet
 */
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
	protected $password_confirmation_value = '';

	/**
	 * @var string
	 */
	protected $password_confirmation_label = '';

	/**
	 * @var callable
	 */
	protected $password_strength_check_callback;

    /**
     * @return Form_Renderer_Abstract_Label|Form_Renderer_Bootstrap_Label
     */
    protected $_tag_label_confirmation;

    /**
     * @return Form_Renderer_Abstract_Field_Abstract|Form_Renderer_Bootstrap_Field_Abstract
     */
    protected $_tag_field_confirmation;

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
	public function getPasswordConfirmationLabel() {
		return $this->getTranslation($this->password_confirmation_label);
	}

	/**
	 * @param string $password_confirmation_label
	 */
	public function setPasswordConfirmationLabel($password_confirmation_label) {
		$this->password_confirmation_label = $password_confirmation_label;
    }



	/**
	 *
	 * @param Data_Array $data
	 */
	public function catchValue( Data_Array $data ) {

		$this->password_confirmation_value = '';
		$name = $this->_name.'_confirmation';

		if($data->exists($name)) {
			$this->password_confirmation_value = trim( $data->getString($name ) );
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

		if(!$this->password_confirmation_value) {
			$this->setValueError(self::ERROR_CODE_CHECK_EMPTY);

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function validateValue() {

		if( $this->_value!=$this->password_confirmation_value ) {
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

    /**
     * @return Form_Renderer_Abstract_Field_Abstract|Form_Renderer_Bootstrap_Field_Abstract
     */
    public function field_confirmation() {
        if(!$this->_tag_field_confirmation) {
            /**
             * @var Form_Renderer_Abstract_Field_Abstract $field
             */
            $field = $this->_getRenderer('Field_'.$this->_type);
            $field->setTagNameValue( $this->getTagNameValue().'_confirmation' );
            $field->setTagId( $this->getId().'_confirmation' );

            $this->_tag_field_confirmation = $field;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->_tag_field_confirmation;
    }


    /**
     * @return Form_Renderer_Abstract_Label|Form_Renderer_Bootstrap_Label
     */
    public function label_confirmation() {
        if(!$this->_tag_label_confirmation) {

            /**
             * @var Form_Renderer_Abstract_Label $label
             */
            $label = $this->_getRenderer('Label');
            $label->setLabel( $this->getPasswordConfirmationLabel() );
            $label->setFor( $this->getId().'_confirmation' );

            $this->_tag_label_confirmation = $label;
        }

        return $this->_tag_label_confirmation;
    }

    /**
     * @return string
     */
	public function render()
    {
        if($this->getIsReadonly()) {
            return '';
        }

        return
            $this->container()
            .$this->error()
            .$this->label()
            .$this->field()
            .$this->container()->end()
            .$this->container()
            .$this->label_confirmation()
            .$this->field_confirmation()
            .$this->container()->end()
            ;
    }
}