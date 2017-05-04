<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Http_Request;

/**
 *
 */
class Installer_Step_CreateAdministrator_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Create administrator account';

	/**
	 *
	 */
	public function main() {
		if(Http_Request::POST()->exists('go')) {
			Installer::goToNext();
		}

		if(Auth_Administrator_User::getList()->getCount()>0) {

			$this->render('created');
		} else {

			$user = new Auth_Administrator_User();
			$form = $user->getRegistrationForm();

			$form->getField('username')->setDefaultValue('admin');


			$user->setLocale( Installer::getCurrentLocale() );

			$this->view->setVar('form', $form);


			if($user->catchForm( $form )) {
				$user->setIsSuperuser(true);
				$user->save();

				Installer::goToNext();
			}

			$this->render('default');
		}

	}

}
