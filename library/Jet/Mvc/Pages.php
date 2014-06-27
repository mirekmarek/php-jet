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

class Mvc_Pages extends Object {
	const HOMEPAGE_ID = '_homepage_';


	/**
	 *
	 * @var Mvc_Pages_Handler_Abstract
	 */
	protected static $_handler = null;

	/**
	 * Returns a list of site pages
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 * @return Mvc_Pages_Page_Abstract[]
	 */
	public static function getPagesList( $site_ID, Locale $locale ) {
		$page = Mvc_Factory::getPageInstance();
		return $page->getList( $site_ID, $locale );
	}

	/**
	 * Returns instance of new site page data object
	 *
	 * @see Mvc_Sites_Page_Abstract
	 * @see Mvc_Sites_Page_Factory
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 * @param string $name
	 * @param string $parent_ID (optional)
	 * @param string $ID (optional)
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public static function getNewPage( $site_ID, Locale $locale , $name, $parent_ID='', $ID=null ) {

		return Mvc_Factory::getPageInstance( $site_ID, $locale , $name, $parent_ID, $ID );
	}


	/**
	 * Return site page data object (or null if does not exist)
	 *
	 * @see Mvc_Pages_Page_Abstract
	 * @see Mvc_Pages_Page_Factory
	 *
	 * @param Mvc_Pages_Page_ID_Abstract $ID
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public static function getPage( Mvc_Pages_Page_ID_Abstract $ID ) {
		return Mvc_Factory::getPageInstance()->load( $ID );
	}

	/**
	 * Create new site page
	 *
	 * @param Mvc_Pages_Page_Abstract $page_data
	 *
	 * @throws Mvc_Sites_Handler_Exception
	 *
	 */
	public static function createPage( Mvc_Pages_Page_Abstract $page_data ) {
		if(!$page_data->validateProperties()) {
			$errors = $page_data->getValidationErrors();
			foreach($errors as $i=>$error) {
				$errors[$i] = (string)$error;
			}
			$errors = implode(', ', $errors);

			throw new Mvc_Sites_Handler_Exception(
				'Page validation failed. Errors: '.$errors,
				Mvc_Sites_Handler_Exception::CODE_INVALID_PAGE_DATA
			);
		}

		self::getHandler()->createPage($page_data);
	}

	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 */
	public static function dropPage( Mvc_Pages_Page_ID_Abstract $page_ID ) {
		self::getHandler()->dropPage( $page_ID );
	}

	/**
	 * @param string $site_ID
	 * @param Locale $locale
	 */
	public static function dropPages(  $site_ID, Locale $locale ) {

		self::getHandler()->dropPages( $site_ID, $locale );
	}


	/**
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 */
	public static function checkPagesData( $site_ID, Locale $locale ) {
		self::getHandler()->checkPagesData( $site_ID, $locale );
	}

	/**
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 *
	 */
	public static function actualizePages( $site_ID, Locale $locale ) {
		self::getHandler()->actualizePages( $site_ID, $locale );
	}


	/**
	 *
	 * @return Mvc_Pages_Handler_Abstract
	 */
	public static function getHandler() {
		if(!self::$_handler) {
			self::$_handler = Mvc_Factory::getPageHandlerInstance();
		}

		return self::$_handler;
	}

	/**
	 *
	 *
	 * @param string $page_ID
	 * @param Locale|string|null $locale (optional), default: current locale
	 * @param DataModel_ID_Abstract|mixed $site_ID( optimal), default: current site_ID
	 *
	 * @return Mvc_Router_Map_URL_Abstract|null
	 */
	public static function getURLObject( $page_ID, $locale=null, $site_ID=null ) {
		return Mvc_Router::getCurrentRouterInstance()->getFrontController()->getURLObject( $page_ID, $locale, $site_ID );
	}

	/**
	 *
	 * @param string $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param null|string $locale (optional, default: auto)
	 * @param null|string $site_ID (optional, default: auto)
	 *
	 * @return string
	 */
	public static function getURI( $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		return Mvc_Router::getCurrentRouterInstance()->getFrontController()->generateURI( $page_ID, $path_fragments, $GET_params, $locale, $site_ID );
	}

	/**
	 *
	 * @param string $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param null|string $locale (optional, default: auto)
	 * @param null|string $site_ID (optional, default: auto)
	 *
	 * @return string
	 */
	public static function getURL(  $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		return Mvc_Router::getCurrentRouterInstance()->getFrontController()->generateURL( $page_ID, $path_fragments, $GET_params, $locale, $site_ID );
	}

	/**
	 *
	 * @param string $page_ID
	 * @param array $path_fragments (optional)
	 * @param array $GET_params (optional)
	 * @param null|string $locale (optional, default: auto)
	 * @param null|string $site_ID (optional, default: auto)
	 *
	 * @return string
	 */
	public static function getNonSchemaURL( $page_ID, array $path_fragments=[], array $GET_params=[], $locale=null, $site_ID=null ) {
		return Mvc_Router::getCurrentRouterInstance()->getFrontController()->generateNonSchemaURL( $page_ID, $path_fragments, $GET_params, $locale, $site_ID );
	}
}