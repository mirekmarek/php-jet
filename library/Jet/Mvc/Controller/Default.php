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
abstract class Mvc_Controller_Default extends Mvc_Controller
{
	/**
	 *
	 *
	 */
	public function handleNotAuthorized(): void
	{
		if(Auth::getCurrentUser()) {
			Mvc::getRouter()->setAccessNotAllowed();
		} else {
			Mvc::getRouter()->setLoginRequired();
		}
	}

}