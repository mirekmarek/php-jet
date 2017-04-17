<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\DataModel_Config;
use Jet\DataModel_Factory;
use Jet\Tr;

class Installer_Step_DataModelBackend_Controller extends Installer_Step_Controller {


	public function main() {
		$main_config = new DataModel_Config(true);

		$config = DataModel_Factory::getBackendConfigInstance($main_config->getBackendType(), true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			Installer::goNext();
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('config', $config);
		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Backend configuration', [], 'DataModelBackend');
	}

	public function getIsSubStep() {
		return true;
	}
}
