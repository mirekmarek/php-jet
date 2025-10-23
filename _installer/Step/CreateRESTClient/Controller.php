<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use JetApplicationModule\REST\Auth\Entity\APIUser;
use JetApplicationModule\REST\Auth\Entity\Role;

/**
 *
 */
class Installer_Step_CreateRESTClient_Controller extends Installer_Step_Controller
{
	
	public const MAIN_ROLE_ID = 'main';
	public const MAIN_ROLE_NAME = 'Main';
	
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

		if( count( APIUser::getList() ) > 0 ) {

			$this->render( 'created' );
		} else {

			$user = new APIUser();
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
		
		if( Role::idExists( $id ) ) {
			return;
		}
		
		$role = new Role();
		$role->setId( $id );
		$role->setName($name);
		
		$avl_privileges = Role::getAvailablePrivilegesList( false );
		
		foreach($avl_privileges as $privilege=>$privilege_data) {
			$values = [];
			
			foreach($privilege_data->getOptions() as $val=>$node) {
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
