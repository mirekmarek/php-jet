<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application;
use Jet\BaseObject;
use Jet\Auth_ControllerInterface;

use Jet\Http_Headers;
use Jet\Data_DateTime;

use JetApplication\Auth_Administrator_User as Administrator;

/**
 *
 */
class Auth_Controller_REST extends BaseObject implements Auth_ControllerInterface
{

	/**
	 *
	 * @var Administrator
	 */
	protected $current_user = false;

	/**
	 *
	 * @return bool
	 */
	public function isUserLoggedIn()
	{

		$user = $this->getCurrentUser();
		if( !$user ) {
			return false;
		}

		if( !$user->isActivated() ) {
			$this->responseNotAuthorized('Yor account is not activated');

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

		if( !$user->getPasswordIsValid() ) {
			$this->responseNotAuthorized('You have to change your password');

			return false;
		}

		if( ( $pwd_valid_till = $user->getPasswordIsValidTill() )!==null&&$pwd_valid_till<=Data_DateTime::now() ) {
			$user->setPasswordIsValid( false );
			$user->save();

			$this->responseNotAuthorized('You have to change your password');
			return false;
		}

		return true;
	}

	/**
	 * @param string $message
	 */
	protected function responseNotAuthorized( $message )
	{

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
	 * @return Administrator|null
	 */
	public function getCurrentUser()
	{
		if( $this->current_user!==false ) {
			return $this->current_user;
		}


		if(
			!isset( $_SERVER['PHP_AUTH_USER'] ) ||
			!isset( $_SERVER['PHP_AUTH_PW'] )
		) {
			$this->responseNotAuthorized('Please enter username and password');
		} else {
			$user = Administrator::getByIdentity( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );

			if( $user ) {
				$this->current_user = $user;
				return $this->current_user;
			}

			$this->responseNotAuthorized('Incorrect username or password');
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

		$user = Administrator::getByIdentity( $username, $password );

		if( !$user ) {
			return false;
		}

		$this->current_user = $user;

		return true;
	}


}