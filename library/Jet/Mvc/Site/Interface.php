<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
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
	public static function createByData( array $data );


	/**
	 * @return Mvc_Site_LocalizedData_Interface[]
	 */
	public static function getUrlMap();

	/**
	 *
	 * @param bool $get_as_string (optional; if TRUE, string values of locales are returned; default: false)
	 *
	 * @return Locale[]|string[]
	 */
	public static function getAllLocalesList( $get_as_string = true );


	/**
	 *
	 * @param string $id
	 *
	 * @return Mvc_Site_Interface|bool
	 */
	public static function get( $id );

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getDefaultSite();




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
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required );

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
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getHomepage( Locale $locale );

	/**
	 *
	 */
	public function saveDataFile();


}