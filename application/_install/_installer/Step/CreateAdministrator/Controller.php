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

use Jet\Http_Request;
use Jet\Tr;

class Installer_Step_CreateAdministrator_Controller extends Installer_Step_Controller {


	public function main() {
		if(Http_Request::POST()->exists('go')) {
			$this->installer->goNext();
		}

		$user = new Auth_Administrator_User();

		$form = $user->getSimpleForm();
		$user->setLocale( $this->installer->getCurrentLocale() );

		$this->view->setVar('form', $form);


		if($user->catchForm( $form )) {
			$user->setIsSuperuser(true);
			$user->save();
			$this->installer->goNext();
		}

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Create administrator account', [], 'CreateAdministrator');
	}
}
