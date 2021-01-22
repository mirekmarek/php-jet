<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Auth
{

	public function authorize(): bool
	{
		/**
		 * @var Mvc_Page $this
		 */
		if( !$this->getIsSecret() ) {
			return true;
		}

		if( !Auth::checkCurrentUser() ) {
			Auth::handleLogin();
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function accessAllowed(): bool
	{
		/**
		 * @var Mvc_Page $this
		 */
		if( !$this->getIsSecret() ) {
			return true;
		}

		return Auth::checkPageAccess( $this );
	}

}