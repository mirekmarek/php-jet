<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\TestModule2;
use Jet;
use Jet\Form;
use Jet\Form_Field_Input;

use Jet\Form_Field_Int;
use Jet\Form_Field_Float;
use Jet\Form_Field_Range;

use Jet\Form_Field_Date;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Time;
use Jet\Form_Field_Week;
use Jet\Form_Field_Month;

use Jet\Form_Field_Email;
use Jet\Form_Field_Tel;

use Jet\Form_Field_Url;
use Jet\Form_Field_Search;

use Jet\Form_Field_Color;

use Jet\Form_Field_Select;
use Jet\Form_Field_MultiSelect;

use Jet\Form_Field_Checkbox;
use Jet\Form_Field_RadioButton;

use Jet\Form_Field_Textarea;
use Jet\Form_Field_WYSIWYG;

use Jet\Form_Field_RegistrationUsername;
use Jet\Form_Field_RegistrationEmail;
use Jet\Form_Field_RegistrationPassword;
use Jet\Form_Field_Password;


//TODO: const TYPE_FILE = 'File';
//TODO: const TYPE_FILE_IMAGE = 'FileImage';


use Jet\Mvc_Controller_Standard;

class Controller_Standard extends Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = null;

	protected static $ACL_actions_check_map = [
		'default' => false,
		'test_action1' => false,
		'test_action2' => false,
	];

	/**
	 *
	 */
	public function initialize() {
	}

	public function default_Action() {
	}

	public function test_action1_Action() {
		$this->render( 'test-action1' );
	}

	public function test_action2_Action() {

		$input_field = new Form_Field_Input( 'input', 'Input');
		$input_field->setPlaceholder('Input field without validation');

		$validated_input_field = new Form_Field_Input( 'validated_input', 'Validated input');
		$validated_input_field->setIsRequired(true);
		$validated_input_field->setPlaceholder('Type ZIP code (NNN NN)');
		$validated_input_field->setValidationRegexp('/^[0-9]{3} [0-9]{2}$/');
		$validated_input_field->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please type ZIP code',
			Form_Field_Float::ERROR_CODE_INVALID_FORMAT => 'Invalid format'
		]);

		$int_field = new Form_Field_Int( 'int', 'Int');
		$int_field->setIsRequired(true);
		$int_field->setMinValue(10);
		$int_field->setMaxValue(100);
		$int_field->setStep(10);
		$int_field->setErrorMessages([
			Form_Field_Int::ERROR_CODE_EMPTY => 'Field is required',
			Form_Field_Int::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
		]);


		$float_field = new Form_Field_Float( 'float', 'Float');
		$float_field->setIsRequired(true);
		$float_field->setMinValue(-0.10);
		$float_field->setMaxValue(3.50);
		$float_field->setStep(0.1);
		$float_field->setErrorMessages([
			Form_Field_Float::ERROR_CODE_EMPTY => 'Field is required',
			Form_Field_Float::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
		]);


		$range_field = new Form_Field_Range( 'range', 'Range');
		$range_field->setIsRequired(true);
		$range_field->setMinValue(10);
		$range_field->setMaxValue(100);
		$range_field->setStep(10);
		$range_field->setErrorMessages([
			Form_Field_Range::ERROR_CODE_EMPTY => 'Field is required',
			Form_Field_Range::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
		]);



		$date_field = new Form_Field_Date('date', 'Date');
		$date_field->setIsRequired(true);
		$date_field->setErrorMessages([
			Form_Field_Date::ERROR_CODE_EMPTY => 'Please specify date',
			Form_Field_Date::ERROR_CODE_INVALID_FORMAT => 'Please specify date',
		]);
		$date_field->setPlaceholder('Date');




		$date_time_field = new Form_Field_DateTime('date_time', 'Date and time');
		$date_time_field->setIsRequired(true);
		$date_time_field->setErrorMessages([
			Form_Field_Date::ERROR_CODE_EMPTY => 'Please specify date and time',
			Form_Field_Date::ERROR_CODE_INVALID_FORMAT => 'Please specify date and time',
		]);
		$date_time_field->setPlaceholder('Date and time');


		$time_field = new Form_Field_Time('time', 'Time');
		$time_field->setErrorMessages([
			Form_Field_Time::ERROR_CODE_EMPTY => 'Please specify time',
			Form_Field_Time::ERROR_CODE_INVALID_FORMAT => 'Invalid format'
		]);

		$week_field = new Form_Field_Week('week', 'Week');
		$week_field->setErrorMessages([
			Form_Field_Week::ERROR_CODE_EMPTY => 'Please specify week',
			Form_Field_Week::ERROR_CODE_INVALID_FORMAT => 'Invalid format'
		]);

		$month_field = new Form_Field_Month('month', 'Month');
		$month_field->setErrorMessages([
			Form_Field_Month::ERROR_CODE_EMPTY => 'Please specify month',
			Form_Field_Month::ERROR_CODE_INVALID_FORMAT => 'Invalid format'
		]);



		$email_field = new Form_Field_Email('email', 'Email');
		$email_field->setIsRequired(true);
		$email_field->setErrorMessages([
			Form_Field_Email::ERROR_CODE_EMPTY => 'Please specify e-mail address',
			Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please specify e-mail address',
		]);
		$date_time_field->setPlaceholder('E-mail address');



		$url_field = new Form_Field_Url('url', 'URL');
		$url_field->setIsRequired(true);
		$url_field->setErrorMessages([
			Form_Field_Email::ERROR_CODE_EMPTY => 'Please specify URL address',
			Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please specify URL address',
		]);
		$url_field->setPlaceholder('URL address');


		$tel_field = new Form_Field_Tel('tel', 'Telephone number');
		$tel_field->setIsRequired(true);
		$tel_field->setValidationRegexp('/^[0-9]{9,12}$/');
		$tel_field->setPlaceholder('Telephone number');
		$tel_field->setErrorMessages([
			Form_Field_Tel::ERROR_CODE_EMPTY => 'Please specify telephone number',
			Form_Field_Tel::ERROR_CODE_INVALID_FORMAT => 'Please specify telephone number',
		]);


		$search_field = new Form_Field_Search('search', 'Search');


		$color_field = new Form_Field_Color('color', 'Color');
		$color_field->setErrorMessages([
			Form_Field_Color::ERROR_CODE_EMPTY => 'Please select color',
			Form_Field_Color::ERROR_CODE_INVALID_FORMAT => 'Invalid format'
		]);


		$select_field = new Form_Field_Select( 'select', 'Select');
		$select_field->setSelectOptions(
			[
				'o1' => 'Option 1',
				'o2' => 'Option 2',
				'o3' => 'Option 3',
				'o4' => 'Option 4',
			]
		);
		$select_field->setErrorMessages([
			Jet\Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]);


		$multi_select_field = new Form_Field_MultiSelect( 'multi_select', 'Multi Select');
		$multi_select_field->setSelectOptions(
			[
				'o1' => 'Option 1',
				'o2' => 'Option 2',
				'o3' => 'Option 3',
				'o4' => 'Option 4',
			]
		);
		$multi_select_field->setErrorMessages([
			Jet\Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]);

		$checkbox_field = new Form_Field_Checkbox( 'checkbox', 'Checkbox');

		$radio_field = new Form_Field_RadioButton( 'radio', 'Radio buttons' );
		$radio_field->setSelectOptions([
			'o1' => 'Option 1',
			'o2' => 'Option 2',
			'o3' => 'Option 3',
			'o4' => 'Option 4',
		]);
		$radio_field->setErrorMessages([
			Jet\Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Invalid value'
		]);

		$textarea_field = new Form_Field_Textarea( 'textarea', 'Text area');
		$wysiwyg_field = new Form_Field_WYSIWYG( 'wysiwyg', 'WYSIWYG');


		$registration_user_name_field = new Form_Field_RegistrationUsername('registration_user_name_field', 'Registration - user name');
		$registration_user_name_field->setErrorMessages([
			Form_Field_RegistrationUsername::ERROR_CODE_EMPTY => 'Please type user name',
			Form_Field_RegistrationUsername::ERROR_CODE_USER_ALREADY_EXISTS => 'Sorry, but username is already used',
		]);
		$registration_user_name_field->setUserExistsCheckCallback( function( $user_name ) {
			return !in_array($user_name, [
				'exists1',
				'exists2',
				'some user name'
			]);
		} );

		$registration_email_field = new Form_Field_RegistrationEmail('registration_email_field', 'Registration - e-mail');
		$registration_email_field->setErrorMessages([
			Form_Field_RegistrationEmail::ERROR_CODE_EMPTY => 'Please type e-mail',
			Form_Field_RegistrationEmail::ERROR_CODE_INVALID_FORMAT => 'Please type e-mail',
			Form_Field_RegistrationEmail::ERROR_CODE_USER_ALREADY_EXISTS => 'Sorry, but e-mail is already used',

		]);
		$registration_email_field->setUserExistsCheckCallback( function( $user_name ) {
			return !in_array($user_name, [
				'exists1@domain.tld',
				'exists2@domain.tld',
				'some.user.name@domain.tld'
			]);
		} );


		$registration_password_field = new Form_Field_RegistrationPassword('registration_password_field', 'Registration - password');
		$registration_password_field->setPasswordCheckLabel('Registration - Confirm password');
		$registration_password_field->setPasswordStrengthCheckCallback( function( $password ) {
			if($password=='stupidpassword') {
				return false;
			}
			return true;
		} );

		$registration_password_field->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY => 'Please type password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY => 'Please confirm password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password and it\'s confirmations does not match.',
			Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD => 'Your password is weak. Please type some better password.'
		]);

		$password_field = new Form_Field_Password('password_field', 'Password');


		$form = new Form( 'TestForm', [
			$input_field,
			$validated_input_field,

			$int_field,
			$float_field,
			$range_field,

			$date_field,
			$date_time_field,
			$time_field,
			$week_field,
			$month_field,

			$email_field,
			$tel_field,

			$url_field,
			$search_field,

			$color_field,

			$select_field,
			$multi_select_field,

			$checkbox_field,
			$radio_field,

			$textarea_field,
			$wysiwyg_field,

			$registration_user_name_field,
			$registration_email_field,
			$registration_password_field,

			$password_field
		]);



		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$this->view->setVar('form_sent', true);
			$this->view->setVar('form_values', $form->getValues());
		}


		//$form->enableDecorator('Dojo');
		$this->view->setVar('form', $form);


		$this->render( 'test-action2' );
	}

}