<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application as Jet_Application;

use Jet\Mvc_Router;

use Jet\Form_Field_WYSIWYG;

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

	/**
	 * @param Mvc_Router $router
	 */
	public static function initWYSIWYG( Mvc_Router $router )
	{
		//TODO:

		$current_locale = $router->getLocale();

		if( $current_locale->getLanguage()!='en' ) {
			Form_Field_WYSIWYG::setDefaultEditorConfigValue(
				'language_url',
				JET_URI_PUBLIC.'scripts/tinymce/language/'.$current_locale->toString().'.js'
			);
		}

	}

}