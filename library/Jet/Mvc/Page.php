<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

class Mvc_Page extends Object {
	const HOMEPAGE_ID = '_homepage_';



	/**
	 * Returns a list of site pages
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Abstract[]
	 */
	public static function getPagesList( $site_ID, Locale $locale ) {
		$page = Mvc_Factory::getPageInstance();
		return $page->getList( $site_ID, $locale );
	}


	/**
	 * Return site page data object (or null if does not exist)
	 *
	 * @see Mvc_Page_Abstract
	 * @see Mvc_Page_Factory
	 *
	 * @param Mvc_Page_ID_Abstract|string $page_ID (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|Mvc_Site_ID_Abstract|null $site_ID (optional, null = current)
	 *
	 * @return Mvc_Page_Abstract
	 */
	public static function get( $page_ID=null, $locale=null, $site_ID=null  ) {
		if(!$page_ID) {
			return Mvc::getCurrentPage();
		}

		$page_i = Mvc_Factory::getPageInstance();

		if( !($page_ID instanceof Mvc_Page_ID_Abstract) ) {

			if(!$locale) {
				$locale = Mvc::getCurrentLocale();
			}
			if(!$site_ID) {
				$site_ID = Mvc::getCurrentSite()->getID();
			}

			$page_ID = Mvc_Factory::getPageIDInstance()->createID( $site_ID, $locale, $page_ID );

		}

		return $page_i->load( $page_ID );
	}


}