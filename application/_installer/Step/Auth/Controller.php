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

class Installer_Step_Auth_Controller extends Installer_Step_Controller {



	public function main() {
		$config = Auth_Factory::getConfigInstance(true);
		$form = $config->getCommonForm();


		if( $config->catchForm($form) ) {
			$config->save();

			$this->installer->goNext();
		}

		$this->view->setVar('form', $form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Authentication configuration', array(), 'MvcRouter');
	}

	/**
	 * @return array|bool
	 */
	public function getStepsAfter() {
        return false;
	}
}