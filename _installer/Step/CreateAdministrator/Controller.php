<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;

use JetApplication\Auth_Administrator_User;
use JetApplication\Auth_RESTClient_User;

/**
 *
 */
class Installer_Step_CreateAdministrator_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected string $label = 'Create administrator account';

	/**
	 *
	 */
	public function main() : void
	{
		$this->catchContinue();

		if( count(Auth_Administrator_User::getList())>0 ) {

			$this->render( 'created' );
		} else {

			$administrator = new Auth_Administrator_User();
			$form = $administrator->getRegistrationForm();

			$form->getField( 'username' )->setDefaultValue( 'admin' );


			$administrator->setLocale( Installer::getCurrentLocale() );

			$this->view->setVar( 'form', $form );


			if( $administrator->catchForm( $form ) ) {
				$administrator->setIsSuperuser( true );
				$administrator->save();


				$api_user = new Auth_RESTClient_User();
				$api_user->setUsername( $administrator->getUsername() );
				$api_user->setLocale( $administrator->getLocale() );
				$api_user->setEmail( $administrator->getEmail() );
				$api_user->setPassword( $form->getField('password')->getValue() );
				$api_user->save();

				Installer::goToNext();
			}

			$this->render( 'default' );
		}

	}

}
