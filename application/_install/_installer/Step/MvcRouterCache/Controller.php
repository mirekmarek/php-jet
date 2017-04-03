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
use Jet\Exception;
use Jet\Tr;

class Installer_Step_MvcRouterCache_Controller extends Installer_Step_Controller {


	public function main() {
		$main_config = Mvc_Factory::getRouterConfigInstance(true);

		$config = Mvc_Factory::getRouterCacheBackendConfigInstance($main_config->getCacheBackendType(), true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

            $OK = true;
            $e = null;
            try {
                Mvc_Factory::getRouterInstance()->helper_cache_create();
            } catch( Exception $e) {
                $OK = false;
            }

            if($OK) {
	            Installer::goNext();
            } else {
                $this->view->setVar('backend_error', $e->getMessage());

                $this->render('backend_error');
                return;
            }
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('config', $config);
		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Cache configuration', [], 'MvcRouterCache');
	}

	public function getIsSubStep() {
		return true;
	}
}
