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
			Form_Factory::field('Input','input', 'Input: '),
			Form_Factory::field('Select','select', 'Select: '),
			Form_Factory::field('Checkbox','checkbox', 'Checkbox: '),
			Form_Factory::field('Int','int', 'Int: '),
		]);

		$form->getField('select')->setSelectOptions(
				[
					'o1' => 'Option 1',
					'o2' => 'Option 2',
					'o3' => 'Option 3',
					'o4' => 'Option 4',
				]
			);

		/**
		 * @var Form_Field_Int $int_field
		 */
		$int_field = $form->getField('int');
		$int_field->setMinValue(10);
		$int_field->setMaxValue(100);

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