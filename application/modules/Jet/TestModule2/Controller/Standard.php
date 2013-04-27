<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule2
 * @subpackage JetApplicationModule_TestModule2_Controller
 */
namespace JetApplicationModule\Jet\TestModule2;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"default" => false,
		"test_action1" => false,
		"test_action2" => false,
	);

	public function default_Action() {
	}

	public function test_action1_Action() {
		$this->render( "test-action1" );
	}

	public function test_action2_Action() {
		$form = new Jet\Form( "TestForm", array(
			Jet\Form_Factory::field("Input","input", "Input: "),
			Jet\Form_Factory::field("Select","select", "Select: "),
			Jet\Form_Factory::field("Checkbox","checkbox", "Checkbox: "),
			Jet\Form_Factory::field("Int","int", "Int: "),
		) );
		
		$form->getField("select")->setSelectOptions(
				array(
					"o1" => "Option 1",
					"o2" => "Option 2",
					"o3" => "Option 3",
					"o4" => "Option 4",
				)
			);

		/**
		 * @var Jet\Form_Field_Int $int_field
		 */
		$int_field = $form->getField("int");
		$int_field->setMinValue(10);
		$int_field->setMaxValue(100);

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$this->view->setVar("form_sent", true);
			$this->view->setVar("form_values", $form->getValues());
		}


		$form->enableDecorator("Dojo");
		$this->view->setVar("form", $form);

		$page = Jet\Mvc::getCurrentPage();
		$page_form = $page->getCommonForm();

		if( $page->catchForm( $page_form ) ) {
			$page->validateProperties();
			$page->save();
			Jet\Mvc::truncateRouterCache();
			Jet\Http_Headers::formSent($page_form);
		}

		$page_form->enableDecorator("Dojo");

		$this->view->setVar("page_form", $page_form);

		$this->render( "test-action2" );
	}

}