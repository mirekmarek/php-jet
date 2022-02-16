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
abstract class MVC_Controller_Default extends MVC_Controller
{
	/**
	 *
	 *
	 */
	public function handleNotAuthorized(): void
	{
		if(Auth::getCurrentUser()) {
			MVC::getRouter()->setAccessNotAllowed();
		} else {
			MVC::getRouter()->setLoginRequired();
		}
	}

}