<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var RESTClient
	 */
	protected $current_user = false;

	/**
	 *
	 * @return bool
	 */
	public function checkCurrentUser()
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
	 * @return RESTClient|null
	 */
	public function getCurrentUser()
	{

		if( $this->current_user!==false ) {
			return $this->current_user;
		}

		if(
			!empty($_SERVER['HTTP_AUTHORIZATION']) &&
			(
				!isset( $_SERVER['PHP_AUTH_USER'] ) ||
				!isset( $_SERVER['PHP_AUTH_PW'] )
			)
		) {

			list(
				$_SERVER['PHP_AUTH_USER'],
				$_SERVER['PHP_AUTH_PW']
			)
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
			$this->current_user = $user;

			return $this->current_user;
		}

		return null;
	}


	/**
	 *
	 */
	public function handleLogin()
	{
	}

	/**
	 * Logout current user
	 */
	public function logout()
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
	public function login( $username, $password )
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
	protected function responseNotAuthorized( $message )
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
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function getCurrentUserHasPrivilege( $privilege, $value )
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
	public function checkModuleActionAccess( $module_name, $action )
	{
		return $this->getCurrentUserHasPrivilege( Auth_RESTClient_Role::PRIVILEGE_MODULE_ACTION, $module_name.':'.$action );
	}


	/**
	 * @param Mvc_Page_Interface $page
	 *
	 * @return bool
	 */
	public function checkPageAccess( Mvc_Page_Interface $page )
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