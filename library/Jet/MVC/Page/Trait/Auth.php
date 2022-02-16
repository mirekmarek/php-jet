<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait MVC_Page_Trait_Auth
{

	/**
	 *
	 * @var bool
	 */
	protected bool $is_secret = false;

	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( bool $is_secret ): void
	{
		$this->is_secret = $is_secret;
	}

	/**
	 * @return bool
	 */
	public function isSecretByDefault(): bool
	{
		if( $this->getBase()->getIsSecret() ) {
			return true;
		}

		if( ($parent = $this->getParent()) ) {
			if( $parent->getIsSecret() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsSecret(): bool
	{
		if( $this->isSecretByDefault() ) {
			return true;
		}

		return $this->is_secret;
	}


	/**
	 * @return bool
	 */
	public function authorize(): bool
	{
		if( !$this->getIsSecret() ) {
			return true;
		}

		$router = MVC::getRouter();

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