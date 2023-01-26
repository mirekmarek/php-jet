<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use JetApplication\Auth_Administrator_User;

/**
 *
 */
class Installer_Step_CreateAdministrator_Controller extends Installer_Step_Controller
{
	protected string $icon = 'user-secret';

	/**
	 * @var string
	 */
	protected string $label = 'Create administrator account';

	/**
	 *
	 */
	public function main(): void
	{
		$this->catchContinue();

		if( count( Auth_Administrator_User::getList() ) > 0 ) {

			$this->render( 'created' );
		} else {

			$administrator = new Auth_Administrator_User();
			$form = $administrator->getRegistrationForm();

			$form->getField( 'username' )->setDefaultValue( 'admin' );


			$administrator->setLocale( Installer::getCurrentLocale() );

			$this->view->setVar( 'form', $form );


			if( $form->catch() ) {
				$administrator->setIsSuperuser( true );
				$administrator->save();

				Installer::goToNext();
			}

			$this->render( 'default' );
		}

	}

}
