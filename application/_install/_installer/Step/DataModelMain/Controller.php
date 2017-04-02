<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\DataModel_Config;
use Jet\Tr;

class Installer_Step_DataModelMain_Controller extends Installer_Step_Controller {


	public function main() {
		$config = new DataModel_Config(true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			$this->installer->goNext();
		}

		$this->view->setVar('form', $form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('DataModel configuration', [], 'DataModelMain');
	}

	public function getStepsAfter() {

		$result = [];

		$result[] = 'DataModelBackend';


		return $result;
	}


}
