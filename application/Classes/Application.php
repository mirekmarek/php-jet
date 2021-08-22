<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application as Jet_Application;

use Jet\Mvc_Router;

use Jet\ErrorPages;

/**
 *
 */
class Application extends Jet_Application
{

	/**
	 * @param Mvc_Router $router
	 */
	public static function initErrorPages( Mvc_Router $router )
	{
		$current_base = $router->getBase();
		$current_locale = $router->getLocale();

		ErrorPages::setErrorPagesDir(
			$current_base->getPagesDataPath(
				$current_locale
			)
		);

	}

}