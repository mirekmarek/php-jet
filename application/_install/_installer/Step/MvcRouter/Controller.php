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

use Jet\Mvc_Factory;
use Jet\Tr;

class Installer_Step_MvcRouter_Controller extends Installer_Step_Controller {



	public function main() {
		$config = Mvc_Factory::getRouterConfigInstance(true);
		$form = $config->getCommonForm();


		if( $config->catchForm($form) ) {
			$config->save();

			Installer::goNext();
		}

		$this->view->setVar('form', $form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Router configuration', [], 'MvcRouter');
	}

	/**
	 * @return array|bool
	 */
	public function getStepsAfter() {
		$config = Mvc_Factory::getRouterConfigInstance(true);
		if(!$config->getCacheEnabled()) {
			return false;
		}

		return ['MvcRouterCache'];
	}
}
