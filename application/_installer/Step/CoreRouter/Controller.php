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

class Installer_Step_CoreRouter_Controller extends Installer_Step_Controller {



	public function main() {
		$config = Mvc_Factory::getRouterConfigInstance(true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			$this->installer->goNext();
		}

		$this->view->setVar("form", $form);

		$this->render("default");
	}

	public function getLabel() {
		return Tr::_("Router configuration", array(), "CoreRouter");
	}

	/**
	 * @return array|bool
	 */
	public function getStepsAfter() {
		$config = Mvc_Factory::getRouterConfigInstance(true);
		if(!$config->getCacheEnabled()) {
			return false;
		}

		return array("CoreRouterCache");
	}
}
