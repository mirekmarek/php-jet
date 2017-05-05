<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Interface Mvc_Site_Interface
 * @package Jet
 */
interface Mvc_Site_Interface
{

	/**
	 *
	 * @param string $id
	 *
	 * @return Mvc_Site_Interface|bool
	 */
	public static function get( $id );

	/**
	 * @param string $id
	 *
	 */
	public function setId( $id );

	/**
	 * @return string
	 */
	public function getId();

	/**
	 *
	 */
	public function generateId();

	/**
	 * Returns site name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 */
	public function setName( $name );

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath();

	/**
	 * @param Locale $locale (optional)
	 *
	 * @return string
	 */
	public function getPagesDataPath( Locale $locale = null );

	/**
	 * @return string
	 */
	public function getLayoutsPath();

	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	public function getDefaultLocale();

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function getHasLocale( Locale $locale );

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getLocalizedData( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface[]
	 */
	public function getURLs( Locale $locale );

	/**
	 * Add URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function addURL( Locale $locale, $URL );

	/**
	 * Remove URL. If the URL was default, then set as the default first possible URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function removeURL( Locale $locale, $URL );

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultURL( Locale $locale, $URL );

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultURL( Locale $locale );

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultSslURL( Locale $locale, $URL );

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public function getDefaultSslURL( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface[]
	 */
	public function getDefaultMetaTags( Locale $locale );

	/**
	 * @param Locale                                   $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface $meta_tag
	 */
	public function addDefaultMetaTag( Locale $locale, Mvc_Site_LocalizedData_MetaTag_Interface $meta_tag );

	/**
	 * @param Locale $locale
	 * @param int    $index
	 */
	public function removeDefaultMetaTag( Locale $locale, $index );

	/**
	 * @param Locale                                     $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Interface[] $meta_tags
	 */
	public function setDefaultMetaTags( Locale $locale, $meta_tags );

	/**
	 * Returns site locales
	 *
	 * @see Mvc_Site
	 *
	 * @param bool $get_as_string (optional, default: false)
	 *
	 * @return Locale[]
	 */
	public function getLocales( $get_as_string = false );

	/**
	 * Add locale
	 *
	 * @param Locale $locale
	 */
	public function addLocale( Locale $locale );

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale );

	/**
	 * Set default locale. Add locale first if is not defined.
	 *
	 * @param Locale $locale
	 */
	public function setDefaultLocale( Locale $locale );

	/**
	 * @return bool
	 */
	public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault( $is_default );

	/**
	 * @return bool
	 */
	public function getIsActive();

	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active );

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getHomepage( Locale $locale );

	/**
	 * @return array|Mvc_Site_LocalizedData_URL_Interface[]
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function getUrlsMap();

	/**
	 * Returns default site data
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getDefault();

	/**
	 *
	 */
	public function setupErrorPagesDir();

	/**
	 * Sends 401 HTTP header and shows the access denied page
	 *
	 */
	public function handleAccessDenied();

	/**
	 *
	 */
	public function handleDeactivatedSite();

	/**
	 *
	 */
	public function handleDeactivatedLocale();

	/**
	 * Sends 404 HTTP header and shows the Page Not Found
	 *
	 */
	public function handle404();

	/**
	 *
	 */
	public function saveDataFile();

	/**
	 *
	 */
	public function saveUrlMapFile();

}