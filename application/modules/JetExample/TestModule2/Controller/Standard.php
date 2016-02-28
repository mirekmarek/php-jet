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
use Jet\Form_Field_Date;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Email;
use Jet\Form_Field_Url;
use Jet\Form_Field_Tel;
use Jet\Form_Field_Search;

use Jet\Form_Field_Select;
use Jet\Form_Field_Checkbox;
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

		/**
		 * @var Form_Field_Int $int_field
		 */
		$int_field = new Form_Field_Int( 'int', 'Int');
		$int_field->setIsRequired(true);
		$int_field->setMinValue(10);
		$int_field->setMaxValue(100);
		$int_field->setStep(10);
		$int_field->setErrorMessages([
			Form_Field_Int::ERROR_CODE_EMPTY => 'Field is required',
			Form_Field_Int::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
		]);


		/**
		 * @var Form_Field_Float $float_field
		 */
		$float_field = new Form_Field_Float( 'float', 'Float');
		$float_field->setIsRequired(true);
		$float_field->setMinValue(-0.10);
		$float_field->setMaxValue(3.50);
		$float_field->setStep(0.1);
		$float_field->setErrorMessages([
			Form_Field_Float::ERROR_CODE_EMPTY => 'Field is required',
			Form_Field_Float::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
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


		$checkbox_field = new Form_Field_Checkbox( 'checkbox', 'Checkbox');



		$form = new Form( 'TestForm', [
			$input_field,
			$validated_input_field,
			$int_field,
			$float_field,
			$date_field,
			$date_time_field,
			$email_field,
			$url_field,
			$tel_field,
			$search_field,

			$select_field,
			$checkbox_field,
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