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
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

class Mvc_Sites extends Object {

	/**
	 *
	 * @var Mvc_Sites_Handler_Abstract
	 */
	protected static $_handler = NULL;


	/**
	 * Returns a list of all sites
	 *
	 * @return Mvc_Sites_Site_Abstract[]
	 */
	public static function getAllSitesList() {
		$site = Mvc_Factory::getSiteInstance();
		return $site->getList();
	}

	/**
	 * Returns instance of new site data object
	 *
	 * @see Mvc_Sites_Site_Abstract
	 * @see Mvc_Sites_Site_Factory
	 *
	 * @param string $name
	 * @param string $ID (optional)
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public static function getNewSite( $name, $ID=null ) {
		$site = Mvc_Factory::getSiteInstance();
		$site->initNew($name, $ID);

		return $site;
	}

	/**
	 * Returns site data object (or NULL if does not exist)
	 *
	 * @see Mvc_Sites_Site_Abstract
	 * @see Mvc_Sites_Site_Factory
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 * @return Mvc_Sites_Site_Abstract
	 */
	public static function getSite( Mvc_Sites_Site_ID_Abstract $ID ) {
		return Mvc_Factory::getSiteInstance()->load($ID);
	}

	/**
	 * Create new site
	 *
	 * @param Mvc_Sites_Site_Abstract $site_data
	 * @param string $template (optional, default:default)
	 * @param bool $activate (optional, default:true)
	 *
	 * @throws Mvc_Sites_Handler_Exception
	 *
	 */
	public static function createSite( Mvc_Sites_Site_Abstract $site_data, $template="default", $activate=true ) {
		if(!$site_data->validateData()) {
			$errors = $site_data->getValidationErrors();
			foreach($errors as $i=>$error) {
				$errors[$i] = (string)$error;
			}
			$errors = implode(", ", $errors);

			throw new Mvc_Sites_Handler_Exception(
				"Page validation failed! Errors: {$errors}",
				Mvc_Sites_Handler_Exception::CODE_INVALID_SITE_DATA
			);
		}

		self::getHandler()->createSite($site_data, $template, $activate);
	}

	/**
	 * Drop site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract $ID
	 *
	 */
	public static function dropSite( $ID ) {
		self::getHandler()->dropSite( $ID );
	}

	/**
	 * Activate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract  $ID
	 *
	 */
	public static function activateSite( Mvc_Sites_Site_ID_Abstract  $ID ) {
		self::getHandler()->activateSite( $ID );
	}

	/**
	 * Deactivate site
	 *
	 * @param Mvc_Sites_Site_ID_Abstract  $ID
	 */
	public static function deactivateSite( Mvc_Sites_Site_ID_Abstract  $ID ) {
		self::getHandler()->deactivateSite( $ID );
	}

	/**
	 *
	 * @return Mvc_Sites_Handler_Abstract
	 */
	public static function getHandler() {
		if(!self::$_handler) {
			self::$_handler = Mvc_Factory::getSiteHandlerInstance();
		}

		return self::$_handler;
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

}