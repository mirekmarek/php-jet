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

}