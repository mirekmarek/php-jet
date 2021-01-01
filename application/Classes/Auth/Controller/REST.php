<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\BaseObject;
use Jet\Auth_Controller_Interface;
use Jet\Mvc_Page_Interface;

use Jet\Debug;
use Jet\Http_Headers;
use Jet\Data_DateTime;

use Jet\Application_Logger;

use JetApplication\Auth_RESTClient_User as RESTClient;

/**
 *
 */
class Auth_Controller_REST extends BaseObject implements Auth_Controller_Interface
{
	const EVENT_LOGIN_FAILED = 'login_failed';
	const EVENT_LOGIN_SUCCESS = 'login_success';

	/**
	 *
	 * @var ?Auth_RESTClient_User
	 */
	protected ?Auth_RESTClient_User $current_user = null;

	/**
	 *
	 * @return bool
	 */
	public function checkCurrentUser() : bool
	{

		$user = $this->getCurrentUser();
		if( !$user ) {
			return false;
		}


		if( $user->isBlocked() ) {
			$till = $user->isBlockedTill();
			if(
				$till!==null &&
				$till<=Data_DateTime::now()
			) {
				$user->unBlock();
				$user->save();
			} else {
				$this->responseNotAuthorized('Yor account is blocked');

				return false;
			}
		}

		return true;
	}


	/**
	 *
	 * @return RESTClient|bool
	 */
	public function getCurrentUser() : RESTClient|bool
	{

		if( $this->current_user!==null ) {
			return $this->current_user;
		}

		if(
			!empty($_SERVER['HTTP_AUTHORIZATION']) &&
			(
				!isset( $_SERVER['PHP_AUTH_USER'] ) ||
				!isset( $_SERVER['PHP_AUTH_PW'] )
			)
		) {

			[
				$_SERVER['PHP_AUTH_USER'],
				$_SERVER['PHP_AUTH_PW']
			]
				= explode(':' , base64_decode( substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

		}

		if(
			!isset( $_SERVER['PHP_AUTH_USER'] ) ||
			!isset( $_SERVER['PHP_AUTH_PW'] )
		) {
			$this->responseNotAuthorized('Please enter username and password');
		} else {
			$user = RESTClient::getByIdentity( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );

			if( !$user ) {
				Application_Logger::warning(
					static::EVENT_LOGIN_FAILED,
					'Login failed. Username: \''.$_SERVER['PHP_AUTH_USER'].'\'',
					$_SERVER['PHP_AUTH_USER'],
					'',
					[],
					false
				);

				$this->responseNotAuthorized('Invalid username or password');
			}

			/**
			 * @var Auth_RESTClient_User $user
			 */
			$this->current_user = $user;

			return $this->current_user;
		}

		return false;
	}


	/**
	 *
	 */
	public function handleLogin() : void
	{
	}

	/**
	 * Logout current user
	 */
	public function logout() : void
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
	public function login( string $username, string $password ) : bool
	{

		$user = RESTClient::getByIdentity( $username, $password );

		if( !$user ) {
			return false;
		}

		$this->current_user = $user;

		return true;
	}


	/**
	 * @param string $message
	 */
	protected function responseNotAuthorized( string $message ) : void
	{
		Debug::setOutputIsJSON( true );

		$error = [
			'result' => 'error',
			'error_code' => 'Not authorized',
			'error_msg'  => $message,
		];

		header( 'WWW-Authenticate: Basic realm="Login"' );
		Http_Headers::authorizationRequired();

		echo json_encode($error);

		Application::end();

	}

	/**
	 *
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( string $privilege, mixed $value ) : bool
	{
		$current_user = $this->getCurrentUser();

		if(
			!$current_user ||
			!($current_user instanceof Auth_RESTClient_User)
		) {
			return false;
		}

		return $current_user->hasPrivilege($privilege, $value);
	}


	/**
	 * @param string $module_name
	 * @param string $action
	 *
	 * @return bool
	 */
	public function checkModuleActionAccess( string $module_name, string $action ) : bool
	{
		return $this->getCurrentUserHasPrivilege( Auth_RESTClient_Role::PRIVILEGE_MODULE_ACTION, $module_name.':'.$action );
	}


	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( Mvc_Page_Interface $page ) : bool
	{

		$current_user = $this->getCurrentUser();

		if(
			!$current_user ||
			!($current_user instanceof Auth_RESTClient_User)
		) {
			return false;
		}

		return true;
	}


}