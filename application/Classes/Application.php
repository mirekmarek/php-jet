<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application as Jet_Application;
use Jet\Mvc_Site;
use Jet\Mvc_View;

/**
 *
 */
class Application extends Jet_Application
{

	/**
	 * @return string
	 */
	public static function getAdminSiteId() {
		return 'admin';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getAdminSite() {
		return Mvc_Site::get( static::getAdminSiteId() );
	}

	/**
	 * @return string
	 */
	public static function getWebSiteId() {
		return 'web';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getWebSite() {
		return Mvc_Site::get( static::getWebSiteId() );
	}

	/**
	 * @return string
	 */
	public static function getRESTSiteId() {
		return 'rest';
	}

	/**
	 * @return Mvc_Site
	 */
	public static function getRESTSite() {
		return Mvc_Site::get( static::getRESTSiteId() );
	}

	/**
	 * @param string $dialog_id
	 * @param array  $options
	 *
	 * @return null|string
	 */
	public static function requireAdminDialog( $dialog_id, array $options=[] ) {

		$page = Mvc_Page::get('dialog-'.$dialog_id);

		if(
			!$page ||
			!$page->getContent()
		) {
			return null;
		}

		$content = $page->getContent()[0];

		$module = $content->getModuleInstance();

		if(!$module) {
			return null;
		}

		$view = new Mvc_View( $module->getViewsDir().'dialog-hooks/' );
		foreach( $options as $k=>$v ) {
			$view->setVar( $k, $v );
		}

		return $view->render( $dialog_id );


	}


}