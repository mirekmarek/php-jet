<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use JetApplication\Auth_RESTClient_User;
use JetApplication\Auth_RESTClient_Role;

/**
 *
 */
class Installer_Step_CreateRESTClient_Controller extends Installer_Step_Controller
{

	const MAIN_ROLE_ID = 'main';
	const MAIN_ROLE_NAME = 'Main';
	
	protected string $icon = 'server';
	
	/**
	 * @var string
	 */
	protected string $label = 'Create REST Client account';

	/**
	 *
	 */
	public function main(): void
	{
		$this->createMainRole();
		
		$this->catchContinue();

		if( count( Auth_RESTClient_User::getList() ) > 0 ) {

			$this->render( 'created' );
		} else {

			$user = new Auth_RESTClient_User();
			$form = $user->getRegistrationForm();
			
			$form->getField( 'username' )->setDefaultValue( 'rest' );
			
			$user->setLocale( Installer::getCurrentLocale() );

			$this->view->setVar( 'form', $form );


			if( $form->catch() ) {
				$user->save();
				$user->setRoles([static::MAIN_ROLE_ID]);

				Installer::goToNext();
			}

			$this->render( 'default' );
		}

	}
	
	public function createMainRole() : void
	{
		$id = static::MAIN_ROLE_ID;
		$name = static::MAIN_ROLE_NAME;
		
		if( Auth_RESTClient_Role::idExists( $id ) ) {
			return;
		}
		
		$role = new Auth_RESTClient_Role();
		$role->setId( $id );
		$role->setName($name);
		
		$avl_privileges = Auth_RESTClient_Role::getAvailablePrivilegesList();
		
		foreach($avl_privileges as $privilege=>$privilege_data) {
			$options_getter = $privilege_data['options_getter'];
			
			$values = [];
			
			foreach(Auth_RESTClient_Role::{$options_getter}() as $val=>$node) {
				$values[] = $val;
			}
			
			$role->setPrivilege(
				$privilege,
				$values
			);
		}
		
		
		$role->save();
	}
	
}
