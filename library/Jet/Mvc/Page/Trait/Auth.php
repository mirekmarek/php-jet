<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Auth
{
	/**
	 *
	 * @var string
	 */
	protected $auth_controller_module_name = '';

	/**
	 *
	 * @var bool
	 */
	protected $is_admin_UI = false;

	/**
	 *
	 * @var bool
	 */
	protected $is_secret_page = false;


	/**
	 * @return bool
	 */
	public function getIsAdminUI() {
		return $this->is_admin_UI;
	}

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI($is_admin_UI) {
		$this->is_admin_UI = (bool)$is_admin_UI;
	}

	/**
	 * @return bool
	 */
	public function getIsSecretPage() {
		return $this->is_secret_page;
	}

	/**
	 * @param bool $is_secret_page
	 */
	public function setIsSecretPage($is_secret_page) {
		$this->is_secret_page = (bool)$is_secret_page;
	}

	/**
	 * @param string $auth_controller_module_name
	 */
	public function setAuthControllerModuleName($auth_controller_module_name)
	{
		$this->auth_controller_module_name = $auth_controller_module_name;
	}

	/**
	 *
	 * @return string
	 */
	public function getAuthControllerModuleName()
	{
		return $this->auth_controller_module_name;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed() {
		if(
			!$this->getIsSecretPage() &&
			!$this->getIsAdminUI()
		) {
			return true;
		}

		if( Auth::getCurrentUserHasPrivilege( Auth_Role::PRIVILEGE_VISIT_PAGE, $this->getKey() ) ) {
			return true;
		}

		return false;

	}

}