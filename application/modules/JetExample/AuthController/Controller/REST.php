<?php
/**
 *
 *
 *
 * Default auth module
 *
 * @see Jet\Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\AuthController;
use Jet;
use Jet\Mvc_Controller_REST;
use Jet\Http_Headers;
use Jet\Auth;

class Controller_REST extends Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;


	const ERR_CODE_AUTHORIZATION_REQUIRED = 'AuthorizationRequired';
	protected static $errors = array(
		self::ERR_CODE_AUTHORIZATION_REQUIRED => array( Http_Headers::CODE_401_UNAUTHORIZED, 'Authorization required'),
	);

	protected static $ACL_actions_check_map = array(
		'login' => false,
		'isNotActivated' => false,
		'isBlocked' => false,
		'mustChangePassword' => false
	);

	/**
	 *
	 */
	public function initialize() {
	}


    /**
     *
     */
    public function login_Action() {

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Login"');

            $this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array('message'=>'User is not logged in'));
        } else {

            if(Auth::login( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] )) {
                Http_Headers::reload();
            } else {
                header('WWW-Authenticate: Basic realm="Login"');

                $this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array('message'=>'Incorrect username or password'));
            }

        }
	}
	

	public function isNotActivated_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array('message'=>'User is not activated'));
	}

	public function isBlocked_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array('message'=>'User is blocked'));
	}

	public function mustChangePassword_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array('message'=>'User must change password'));
	}
}