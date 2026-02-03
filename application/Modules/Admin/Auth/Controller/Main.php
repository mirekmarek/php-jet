<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Admin\Auth\Controller;

use Jet\Application_Module;
use Jet\Auth_User_Interface;
use Jet\Data_DateTime;
use Jet\Logger;
use Jet\MVC_Page_Interface;
use Jet\Session;
use JetApplication\Application_Service_Admin;
use JetApplication\Application_Service_Admin_Auth_Controller;
use JetApplication\Application_Service_Admin_Auth_LoginModule;
use JetApplicationModule\Admin\Auth\Entity\Role;
use JetApplicationModule\Admin\Auth\Entity\Administrator;

/**
 *
 */
class Main extends Application_Module implements Application_Service_Admin_Auth_Controller
{
	
	public const EVENT_LOGIN_FAILED = 'login_failed';
	public const EVENT_LOGIN_SUCCESS = 'login_success';
	public const EVENT_LOGOUT = 'logout';
	
	/**
	 *
	 * @var Administrator|false|null
	 */
	protected Administrator|false|null $current_user = null;
	
	/**
	 *
	 * @return bool
	 */
	public function checkCurrentUser(): bool
	{
		
		$user = $this->getCurrentUser();
		if( !$user ) {
			return false;
		}
		
		if( $user->isBlocked() ) {
			$till = $user->isBlockedTill();
			if( $till !== null && $till <= Data_DateTime::now() ) {
				$user->unBlock();
				$user->save();
			} else {
				return false;
			}
		}
		
		if( !$user->getPasswordIsValid() ) {
			return false;
		}
		
		if( ($pwd_valid_till = $user->getPasswordIsValidTill()) !== null && $pwd_valid_till <= Data_DateTime::now() ) {
			$user->setPasswordIsValid( false );
			$user->save();
			
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @return Administrator|false
	 */
	public function getCurrentUser(): Administrator|false
	{
		if( $this->current_user !== null ) {
			return $this->current_user;
		}
		
		$user_id = $this->getSession()->getValue( 'user_id' );
		
		if( $user_id ) {
			$this->current_user = Administrator::get( $user_id );
		}
		
		if( !$this->current_user ) {
			$this->current_user = false;
		}
		
		return $this->current_user;
	}
	
	/**
	 * @return Session
	 */
	protected function getSession(): Session
	{
		return new Session( 'auth_admin' );
	}
	
	
	/**
	 *
	 */
	public function handleLogin(): void
	{
		$module = Application_Service_Admin::AuthLoginModule();
		/**
		 * @var Application_Service_Admin_Auth_LoginModule $module
		 */
		$module->handleLogin( $this );
		
	}
	
	/**
	 *
	 */
	public function logout(): void
	{
		$user = $this->getCurrentUser();
		if( $user ) {
			Logger::info(
				event: static::EVENT_LOGOUT,
				event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') logged out',
				context_object_id: $user->getId(),
				context_object_name: $user->getName()
			);
		}
		
		Session::destroy();
		$this->current_user = null;
	}
	
	/**
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function login( string $username, string $password ): bool
	{
		$user = Administrator::getByIdentity( $username, $password );
		
		if( !$user ) {
			Logger::warning(
				event: static::EVENT_LOGIN_FAILED,
				event_message: 'Login failed. Username: \'' . $username . '\'',
				context_object_id: $username,
			);
			
			return false;
		}
		
		
		/**
		 * @var Administrator $user
		 */
		$session = $this->getSession();
		$session->setValue( 'user_id', $user->getId() );
		
		$this->current_user = $user;
		
		Logger::success(
			event: static::EVENT_LOGIN_SUCCESS,
			event_message: 'User ' . $user->getUsername() . ' (id:' . $user->getId() . ') logged in',
			context_object_id: $user->getId(),
			context_object_name: $user->getName()
		);
		
		return true;
	}
	
	
	/**
	 * @param Auth_User_Interface $user
	 * @return bool
	 */
	public function loginUser( Auth_User_Interface $user ) : bool
	{
		return false;
	}
	
	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( string $privilege, mixed $value=null ): bool
	{
		$current_user = $this->getCurrentUser();
		
		if( !$current_user ) {
			return false;
		}
		
		return $current_user->hasPrivilege( $privilege, $value );
	}
	
	
	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public function checkModuleActionAccess( string $module_name, string $action ): bool
	{
		return $this->getCurrentUserHasPrivilege( Role::PRIVILEGE_MODULE_ACTION, $module_name . ':' . $action );
	}
	
	
	/**
	 * @param MVC_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( MVC_Page_Interface $page ): bool
	{
		return $this->getCurrentUserHasPrivilege( Role::PRIVILEGE_VISIT_PAGE, $page->getId() );
	}

}