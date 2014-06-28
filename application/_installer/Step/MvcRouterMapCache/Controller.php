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

class Installer_Step_MvcRouterMapCache_Controller extends Installer_Step_Controller {


	public function main() {
		$main_config = Mvc_Factory::getRouterConfigInstance(true);

		$config = Mvc_Factory::getRouterMapCacheBackendConfigInstance($main_config->getMapCacheBackendType(), true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			Mvc_Router::getNewRouterInstance()->helper_mapCache_create();

			$this->installer->goNext();
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('config', $config);
		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Map Cache configuration', array(), 'MvcRouterMapCache');
	}

	public function getIsSubStep() {
		return true;
	}
}
