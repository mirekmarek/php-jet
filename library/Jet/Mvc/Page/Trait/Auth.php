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
	 * @return bool
	 */
	public function getAccessAllowed()
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_Auth $this
		 */
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