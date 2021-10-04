<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Auth
{

	public function authorize(): bool
	{
		if( !$this->getIsSecret() ) {
			return true;
		}

		$router = Mvc::getRouter();

		if( !Auth::checkCurrentUser() ) {
			$router->setLoginRequired();
			return false;
		}

		if( !Auth::checkPageAccess( $this ) ) {
			$router->setAccessNotAllowed();

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function accessAllowed(): bool
	{
		if( !$this->getIsSecret() ) {
			return true;
		}

		return Auth::checkPageAccess( $this );
	}

}