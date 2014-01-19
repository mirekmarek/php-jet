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

class Installer_Step_CreateAdministrator_Controller extends Installer_Step_Controller {


	public function main() {
		if(Http_Request::POST()->exists('go')) {
			$this->installer->goNext();
		}

		$user = Auth_Factory::getUserInstance();
		$user->initNewObject();

		$form = $user->getSimpleForm();
		$user->setLocale( $this->installer->getCurrentLocale() );

		$this->view->setVar('form', $form);


		if($user->catchForm( $form )) {
			$user->setIsSuperuser(true);
			$user->validateProperties();
			$user->save();
			$this->installer->goNext();
		}

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Create administrator account', array(), 'CreateAdministrator');
	}
}
