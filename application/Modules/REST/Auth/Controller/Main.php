<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\REST\Auth\Controller;

use Jet\Application_Module;
use Jet\Auth_User_Interface;
use Jet\Data_DateTime;
use Jet\Data_Text;
use Jet\Debug;
use Jet\Http_Headers;
use Jet\Logger;
use Jet\MVC_Page_Interface;
use JetApplication\Application;
use JetApplication\Application_Service_REST_Auth_Controller;
use JetApplicationModule\REST\Auth\Entity\Role;
use JetApplicationModule\REST\Auth\Entity\APIUser;

/**
 *
 */
class Main extends Application_Module implements Application_Service_REST_Auth_Controller
{
	public const EVENT_LOGIN_FAILED = 'login_failed';
	public const EVENT_LOGIN_SUCCESS = 'login_success';
	
	/**
	 *
	 * @var APIUser|null|false
	 */
	protected APIUser|null|false $current_user = null;
	
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
			if(
				$till !== null &&
				$till <= Data_DateTime::now()
			) {
				$user->unBlock();
				$user->save();
			} else {
				$this->responseNotAuthorized( 'Yor account is blocked' );
				
				return false;
			}
		}
		
		return true;
	}
	
	
	/**
	 *
	 * @return APIUser|false
	 */
	public function getCurrentUser(): APIUser|false
	{
		
		if( $this->current_user !== null ) {
			return $this->current_user;
		}
		
		if(
			!empty( $_SERVER['HTTP_AUTHORIZATION'] ) &&
			(
				!isset( $_SERVER['PHP_AUTH_USER'] ) ||
				!isset( $_SERVER['PHP_AUTH_PW'] )
			)
		) {
			
			[
				$_SERVER['PHP_AUTH_USER'],
				$_SERVER['PHP_AUTH_PW']
			]
				= explode( ':', base64_decode( substr( $_SERVER['HTTP_AUTHORIZATION'], 6 ) ) );
			
		}
		
		if(
			!isset( $_SERVER['PHP_AUTH_USER'] ) ||
			!isset( $_SERVER['PHP_AUTH_PW'] )
		) {
			$this->responseNotAuthorized( 'Please enter username and password' );
		} else {
			if($this->login( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] )) {
				/** @phpstan-ignore return.type */
				return $this->current_user;
			}
		}
		
		return false;
	}
	
	
	/**
	 *
	 */
	public function handleLogin(): void
	{
	}
	
	/**
	 * Logout current user
	 */
	public function logout(): void
	{
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
		$user = APIUser::getByIdentity( $username, $password );
		
		if( !$user ) {
			$this->current_user = false;
			
			Logger::warning(
				event: static::EVENT_LOGIN_FAILED,
				event_message: 'Login failed. Username: \'' . Data_Text::htmlSpecialChars($_SERVER['PHP_AUTH_USER']) . '\'',
				context_object_id: Data_Text::htmlSpecialChars($_SERVER['PHP_AUTH_USER']),
			);
			
			$this->responseNotAuthorized( 'Invalid username or password' );
			
			return false;
		}
		
		$this->current_user = $user;
		
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
	 * @param string $message
	 */
	protected function responseNotAuthorized( string $message ): void
	{
		Debug::setOutputIsJSON( true );
		
		$error = [
			'result'     => 'error',
			'error_code' => 'Not authorized',
			'error_msg'  => $message,
		];
		
		Http_Headers::response(
			Http_Headers::CODE_401_UNAUTHORIZED,
			[
				'WWW-Authenticate' => 'Basic realm="Login"'
			]
		);
		
		echo json_encode( $error );
		
		Application::end();
		
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
		
		$current_user = $this->getCurrentUser();
		
		if( !$current_user ) {
			return false;
		}
		
		return true;
	}

}