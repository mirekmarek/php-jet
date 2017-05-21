<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Mvc_Site_Interface
{
	/**
	 * @return array
	 */
	public static function loadSitesData();

	/**
	 *
	 * @return Mvc_Site[]
	 */
	public static function loadSites();

	/**
	 * @param array  $data
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function createSiteByData( array $data );


	/**
	 * @return Mvc_Site_LocalizedData_Interface[]
	 */
	public static function getUrlMap();

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
	 * @return array
	 */
	public function getURLs( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 * @param array $URLs
	 */
	public function setURLs( Locale $locale, array $URLs );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return string
	 */
	public function getDefaultURL( Locale $locale );

	/**
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required );

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
	 *
	 *
	 * @param bool $get_as_string (optional, default: false)
	 *
	 * @return Locale[]
	 */
	public function getLocales( $get_as_string = false );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
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
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getDefaultSite();

	/**
	 *
	 */
	public function saveDataFile();


}