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
 * @category JetApplicationModule\Jet\DefaultAuth
 */
namespace JetApplicationModule\Jet\DefaultAuth;
use Jet;

class Controller_REST extends Jet\Mvc_Controller_REST {
	/**
	 *
	 * @var Main
	 */
	protected $module_instance = NULL;


	const ERR_CODE_AUTHORIZATION_REQUIRED = "AuthorizationRequired";
	protected static $errors = array(
		self::ERR_CODE_AUTHORIZATION_REQUIRED => array(Jet\Http_Headers::CODE_401_UNAUTHORIZED, "Authorization required"),
	);

	protected static $ACL_actions_check_map = array(
		"login" => false,
		"isNotActivated" => false,
		"isBlocked" => false,
		"mustChangePassword" => false
	);

	public function login_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array("message"=>"User is not logged in"));
	}
	

	public function isNotActivated_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array("message"=>"User is not activated"));
	}

	public function isBlocked_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array("message"=>"User is blocked"));
	}

	public function mustChangePassword_Action() {
		$this->responseError(self::ERR_CODE_AUTHORIZATION_REQUIRED, array("message"=>"User must change password"));
	}
}