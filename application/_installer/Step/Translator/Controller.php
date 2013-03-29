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

class Installer_Step_Translator_Controller extends Installer_Step_Controller {



	public function main() {
		$config = new Translator_Config(true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			$this->installer->goNext();
		}

		$this->view->setVar("form", $form);

		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Translator configuration", array(), "Translator");
	}

	public function getStepsAfter() {
		return array("TranslatorBackend");
	}
}
