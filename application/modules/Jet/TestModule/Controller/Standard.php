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
 * @category JetApplicationModule
 * @package JetApplicationModule_TestModule
 * @subpackage JetApplicationModule_TestModule_Controller
 */
namespace JetApplicationModule\Jet\TestModule;
use Jet;

class Controller_Standard extends Jet\Mvc_Controller_Standard {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;

	protected static $ACL_actions_check_map = array(
		"default" => false,
		"main_menu" => false,
		"test_action2" => false,
	);

	public function default_Action() {
	}

	public function main_menu_Action() {

		$this->view->setVar("site_tree_current", Jet\Mvc::getCurrentUIManagerModuleInstance()->getSiteStructure(
			Jet\Mvc::getCurrentSiteID(),
			Jet\Mvc::getCurrentLocale()
		));

		foreach( Jet\Mvc::getCurrentLocalesList() as $locale ) {
			$key = "site_tree_".$locale;
			$this->view->setVar( $key, Jet\Mvc::getCurrentUIManagerModuleInstance()->getSiteStructure(
				Jet\Mvc::getCurrentSiteID(),
				$locale
			));

		}

		$this->render("main-menu" );
	}

	public function test_action2_Action( $parameter_1 = "undefined", $parameter_2 = "undefined" ) {
		$this->view->setVar("parameter_1", $parameter_1);
		$this->view->setVar("parameter_2", $parameter_2);

		$obj = new DataModelT1();

		$form = $obj->getCommonForm();

		if(
			$form->catchValues() &&
			$form->validateValues()
		) {
			$this->view->setVar("form_data", $form->getValues());
		}
		$form->enableDecorator("Dojo");
		$this->view->setVar("form", $form);

		$this->view->setVar("parameter_1", $parameter_1);
		$this->view->setVar("parameter_2", $parameter_2);

		$this->render("test-action2" );
	}

}