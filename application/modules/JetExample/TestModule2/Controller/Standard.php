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
use Jet\Form_Factory;
use Jet\Form_Field_Int;
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

		$form = new Form( 'TestForm', [
			Form_Factory::field(Form::TYPE_INPUT,'input', 'Input: '),
			Form_Factory::field(Form::TYPE_SELECT,'select', 'Select: '),
			Form_Factory::field(Form::TYPE_CHECKBOX,'checkbox', 'Checkbox: '),
			Form_Factory::field(Form::TYPE_INT,'int', 'Int: '),
		]);

        $select_field = $form->getField('select');
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

		/**
		 * @var Form_Field_Int $int_field
		 */
		$int_field = $form->getField('int');
		$int_field->setMinValue(10);
		$int_field->setMaxValue(100);
        $int_field->setErrorMessages([
            Form_Field_Int::ERROR_CODE_OUT_OF_RANGE => 'Out of range'
        ]);

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$this->view->setVar('form_sent', true);
			$this->view->setVar('form_values', $form->getValues());
		}


		$form->enableDecorator('Dojo');
		$this->view->setVar('form', $form);


		$this->render( 'test-action2' );
	}

}