<?php
/**
 *
 *
 *
 * Basic class describing Site (@see Mvc_Sites)
 * @see DataModel
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

/**
 * Class Mvc_Site_Abstract
 *
 * @JetDataModel:name = 'site'
 * @JetDataModel:ID_class_name = 'Mvc_Site_ID_Abstract'
 */
abstract class Mvc_Site_Abstract extends DataModel {

	/**
	 * @param string $ID
	 *
	 */
	abstract public function setID( $ID );

	/**
	 * Returns site name
	 *
	 * @return string
	 */
	abstract public function getName();


	/**
	 * @param string $name
	 */
	abstract public function setName($name);

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	abstract public function getBasePath();

    /**
     * @param Locale $locale (optional)
     * @return string
     */
    abstract public function getPagesDataPath( Locale $locale=null );

	/**
	 * @return string
	 */
	abstract public function getLayoutsPath();

    /**
     * @return string
     */
    abstract public function getBaseURI();

    /**
     * @return string
     */
    abstract public function getImagesURI();

    /**
     * @return string
     */
    abstract public function getImagesPath();


    /**
     * @return string
     */
    abstract public function getScriptsURI();

    /**
     * @return string
     */
    abstract public function getScriptsPath();

    /**
     * @return string
     */
    abstract public function getStylesURI();

    /**
     * @return string
     */
    abstract public function getStylesPath();

	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	abstract public function getDefaultLocale();

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	abstract public function getHasLocale( Locale $locale );

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Abstract
	 */
	abstract public function getLocalizedData( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract[]
	 */
	abstract public function getURLs( Locale $locale );

	/**
	 * Add URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	abstract public function addURL( Locale $locale, $URL );

	/**
	 * Remove URL. If the URL was default, then set as the default first possible URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	abstract public function removeURL( Locale $locale, $URL );

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	abstract public function setDefaultURL( Locale $locale, $URL );

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	abstract public function getDefaultURL( Locale $locale );

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	abstract public function setDefaultSslURL( Locale $locale, $URL );

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	abstract public function getDefaultSslURL( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 * @return Mvc_Site_LocalizedData_MetaTag_Abstract[]
	 */
	abstract public function getDefaultMetaTags( Locale $locale );

	/**
	 * @param Locale $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract $meta_tag
	 */
	abstract public function addDefaultMetaTag( Locale $locale, Mvc_Site_LocalizedData_MetaTag_Abstract $meta_tag);

	/**
	 * @param Locale $locale
	 * @param int $index
	 */
	abstract public function removeDefaultMetaTag( Locale $locale, $index );

	/**
	 * @param Locale $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract[] $meta_tags
	 */
	abstract public function  setDefaultMetaTags( Locale $locale, $meta_tags );


	/**
	 * Returns site locales
	 *
	 * @see Site
	 *
	 * @param bool $get_as_string (optional, default: false)
	 *
	 * @return Locale[]
	 */
	abstract public function getLocales( $get_as_string=false );

	/**
	 * Add locale
	 *
	 * @param Locale $locale
	 */
	abstract public function addLocale( Locale $locale );

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
	 *
	 * @param Locale $locale
	 */
	abstract public function removeLocale( Locale $locale );

	/**
	 * Set default locale. Add locale first if is not defined.
	 *
	 * @param Locale $locale
	 */
	abstract public function setDefaultLocale( Locale $locale );

	/**
	 * @return bool
	 */
	abstract public function getIsDefault();

	/**
	 * @param bool $is_default
	 */
	abstract public function setIsDefault($is_default);

	/**
	 * @return bool
	 */
	abstract public function getIsActive();

	/**
	 * @param bool $is_active
	 */
	abstract public function setIsActive($is_active);

    /**
     * @param Locale $locale
     *
     * @return Mvc_Page_Abstract
     */
    abstract public function getHomepage( Locale $locale );

    /**
     * @return array|Mvc_Site_LocalizedData_URL_Abstract[]
     *
     * @throws Mvc_Router_Exception
     */
    abstract public function getUrlsMap();


	/**
	 * Returns a list of all sites
	 *
	 * @return Mvc_Site_Abstract[]
	 */
	abstract public function getList();

	/**
	 * Returns default site data
	 *
	 * @return Mvc_Site_Abstract
	 */
	abstract public function getDefault();


    /**
     *
     */
    abstract public function setupErrorPagesDir();

    /**
     * Sends 401 HTTP header and shows the access denied page
     *
     */
    abstract public function handleAccessDenied();

    /**
     *
     */
    abstract public function handleDeactivatedSite();

    /**
     *
     */
    abstract public function handleDeactivatedLocale();

    /**
     * Sends 404 HTTP header and shows the Page Not Found
     *
     */
    abstract public function handle404();

    /**
     * @param array &$data
     */
    abstract public function readCachedData(&$data);

    /**
     * @param &$data
     */
    abstract public function writeCachedData(&$data);

    /**
     * @param string $template
     */
    abstract public function create( $template );

}