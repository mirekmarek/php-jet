<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use JetApplicationModule\Web\Auth\Entity\Visitor;
use JetApplicationModule\Web\Auth\Entity\Role;

/**
 *
 */
class Installer_Step_CreateVisitor_Controller extends Installer_Step_Controller
{
	
	public const MAIN_ROLE_ID = 'main';
	public const MAIN_ROLE_NAME = 'Main';
	
	protected string $icon = 'users';
	
	/**
	 * @var string
	 */
	protected string $label = 'Create visitor account';

	/**
	 *
	 */
	public function main(): void
	{
		$this->createMainRole();
		
		$this->catchContinue();

		if( count( Visitor::getList() ) > 0 ) {

			$this->render( 'created' );
		} else {

			$user = new Visitor();
			$form = $user->getRegistrationForm();
			
			$form->getField( 'username' )->setDefaultValue( 'visitor' );
			
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
