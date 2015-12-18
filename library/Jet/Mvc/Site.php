<?php
/**
 *
 *
 *
 * Site is the main entity of the system.
 *
 * System is able to operate N of Sites.
 * There is always one Site which has default flag in the system.
 *
 * Each site has from 1 to N locales.
 *
 * Locale consists of the language code (ISO 639-1) and the country code (ISO 3166)
 * One locale always has default flag.
 * The system operates locales as instances of Locale
 *
 * Each Site Locale has from 1 to N URIs. Each URI must be unique in the system.
 *
 * The format of the URI can be:
 *
 * domain.tld
 * subdomain.domain.tld
 * domain.tld/path
 * subdomain.domain.tld/directory
 *
 * One URI always has default flag.
 *
 * When the system catches the request for an URI that is not default, then then request is redirected to the default URI.
 * When the system catches the request for an URI which does not exist in the system, then then request is redirected to the default URI of the default site.
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Site extends Object {


	/**
	 * @var array|Mvc_Site_Abstract[]
	 */
	protected static $_loaded = [];

	/**
	 * Returns a list of all sites
	 *
	 * @return Mvc_Site_Abstract[]
	 */
	public static function getList() {

		$site = Mvc_Factory::getSiteInstance();

		return $site->getList();
	}


	/**
	 * Returns site data object (or null if does not exist)
	 *
	 * @see Mvc_Site_Abstract
	 * @see Mvc_Site_Factory
	 *
	 * @param Mvc_Site_ID_Abstract|string $ID
	 * @return Mvc_Site_Abstract
	 */
	public static function get( $ID ) {

		$ID_s = (string)$ID;

		if(!isset(static::$_loaded[$ID_s])) {
			if(is_string($ID)) {
				$ID = Mvc_Factory::getSiteIDInstance()->createID( $ID );
			}

			$class_name = Mvc_Factory::getSiteClassName();
			/**
			 * @var Mvc_Site_Abstract $class_name
			 */
			static::$_loaded[$ID_s] = $class_name::load( $ID );

		}

		return static::$_loaded[$ID_s];
	}

	/**
	 * @static
	 *
	 * @return array
	 */
	public static function getAvailableTemplatesList() {
		$res = IO_Dir::getSubdirectoriesList(JET_TEMPLATES_SITES_PATH);

		return array_combine($res, $res);
	}

	/**
	 * Returns a list of all locales for all sites
	 *
	 * @param bool $get_as_string (optional; if TRUE, string values of locales are returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getAllLocalesList($get_as_string = true) {
		$sites = static::getList();
		$locales = [];

		if($get_as_string) {

			foreach( $sites as $site ) {
				foreach( $site->getLocales(false) as $locale ) {
					$locales[(string)$locale] = $locale->getName();
				}
			}

			asort($locales);

		} else {
			foreach( $sites as $site ) {
				/**
				 * @var Mvc_Site_Abstract $site
				 */
				foreach( $site->getLocales(false) as $locale ) {
					$locales[(string)$locale] = $locale;
				}
			}
		}

		return $locales;
	}
}