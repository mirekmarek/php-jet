<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
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
	public function accessAllowed()
	{
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_Auth $this
		 */
		if( !$this->getIsSecret() ) {
			return true;
		}

		/** @noinspection PhpParamsInspection */
		return Auth::checkPageAccess( $this );

	}

}