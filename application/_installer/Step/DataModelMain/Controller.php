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
		return Tr::_('DataModel configuration', array(), 'DataModelMain');
	}

	public function getStepsAfter() {
		$config = new DataModel_Config(true);

		$result = array();

		if($config->getCacheEnabled()) {
			$result[] = 'DataModelHistory';
		}

		if($config->getCacheEnabled()) {
			$result[] = 'DataModelCache';
		}

		$result[] = 'DataModelBackend';

		//return array('DataModelBackend');

		return $result;
	}


}
