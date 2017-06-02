<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\BaseObject;
use Jet\Auth_ControllerInterface;

use Jet\Mvc;
use Jet\Mvc_Factory;
use Jet\Mvc_Layout;

use Jet\Session;

use Jet\Data_DateTime;

use JetApplication\Mvc_Page as Page;
use JetApplication\Auth_Administrator_User as Administrator;

//TODO: kompletne oddelit
//TODO: odstranit admin
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
			//TODO:
			return false;
		}

		if( !$user->isActivated() ) {
			//TODO:
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
				//TODO:
				return false;
			}
		}

		if( !$user->getPasswordIsValid() ) {
			return false;
		}

		if( ( $pwd_valid_till = $user->getPasswordIsValidTill() )!==null&&$pwd_valid_till<=Data_DateTime::now() ) {
			$user->setPasswordIsValid( false );
			$user->save();

			//TODO:
			return false;
		}

		return true;
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


		if( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
			header( 'WWW-Authenticate: Basic realm="Login"' );
			header( 'HTTP/1.0 401 Unauthorized' );
		} else {
			$user = Administrator::getByIdentity( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );

			if( $user ) {
				$this->current_user = $user;
			}
		}



		return $this->current_user;
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