<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_InstallModules_Controller extends Installer_Step_Controller {



	public function main() {
		$modules = Application_Modules::getAllModulesList(true);


		$form = new Form("modules_select_form", array(
			Form_Factory::field("MultiSelect", "modules")
		));

		$this->view->setVar("modules", $modules);

		$form->getField("modules")->setSelectOptions( $modules );

		if(Http_Request::POST()->exists("go")) {
			$this->installer->goNext();
		}

		if($form->catchValues() && $form->validateValues()) {
			$d = $form->getValues();
			$selected_modules = $d["modules"];

			$result = array();

			$OK = true;

			foreach($selected_modules as $module) {
				$result[$module] = true;

				if($modules[$module]->getIsActivated()) {
					continue;
				}

				try {
					Application_Modules::installModule($module);
				} catch(Exception $e) {
					$result[$module] = $e->getMessage();

					$OK = false;
				}

				if($result[$module]!==true) {
					continue;
				}

				try {
					Application_Modules::activateModule($module);
				} catch(Exception $e) {
					$result[$module] = $e->getMessage();
					$OK = false;
				}

			}

			$this->view->setVar("result", $result);
			$this->view->setVar("OK", $OK);

			$this->render("modules-installation-result");
		} else {
			$this->view->setVar("form", $form);
			$this->render("default");
		}

	}

	public function getLabel() {
		return Tr::_("Modules installation", array(), "InstallModules");
	}
}
