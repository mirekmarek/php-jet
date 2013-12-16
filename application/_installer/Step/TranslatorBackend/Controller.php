<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_TranslatorBackend_Controller extends Installer_Step_Controller {


	public function main() {
		$main_config = new Translator_Config(true);

		$config = $main_config->getBackendConfig(true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			Translator::helper_create();

			$this->installer->goNext();
		}

		$this->view->setVar("form", $form);
		$this->view->setVar("config", $config);
		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Backend configuration", array(), "TranslatorBackend");
	}

	public function getIsSubstep() {
		return true;
	}
}
