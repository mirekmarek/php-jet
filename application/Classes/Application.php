<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
		$current_site = $router->getSite();
		$current_locale = $router->getLocale();

		ErrorPages::setErrorPagesDir(
			$current_site->getPagesDataPath(
				$current_locale
			)
		);

	}

}