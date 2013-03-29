<?php
/**
 *
 *
 *
 * Basic class describing Site (@see Mvc_Sites)
 * @see DataModel
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
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

abstract class Mvc_Sites_Site_Abstract extends DataModel {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Mvc_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getSiteInstance";
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Mvc_Sites_Site_Abstract";

	/**
	 * Prepares new site data
	 *
	 * @param string $name
	 * @param string $ID (optional)
	 */
	abstract public function initNew( $name, $ID=null );

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
	 * @return string
	 */
	abstract public function getLayoutsPath();

	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	abstract public function getDefaultLocale();

	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Sites_Site_LocalizedData_Abstract
	 */
	abstract public function getLocalizedData( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Sites_Site_LocalizedData_URL_Abstract[]
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
	 * @return Mvc_Sites_Site_LocalizedData_URL_Abstract
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
	 * @return string
	 */
	abstract public function getDefaultSslURL( Locale $locale );

	/**
	 *
	 * @param Locale $locale
	 * @return Mvc_Sites_Site_LocalizedData_MetaTag_Abstract[]
	 */
	abstract public function getDefaultMetaTags( Locale $locale );

	/**
	 * @param Locale $locale
	 * @param Mvc_Sites_Site_LocalizedData_MetaTag_Abstract $meta_tag
	 */
	abstract public function addDefaultMetaTag( Locale $locale, Mvc_Sites_Site_LocalizedData_MetaTag_Abstract $meta_tag);

	/**
	 * @param Locale $locale
	 * @param int $index
	 */
	abstract public function removeDefaultMetaTag( Locale $locale, $index );

	/**
	 * @param Locale $locale
	 * @param Mvc_Sites_Site_LocalizedData_MetaTag_Abstract[] $meta_tags
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
	 * Returns a list of all sites
	 *
	 * @return Mvc_Sites_Site_Abstract[]
	 */
	abstract public function getList();

	/**
	 * Returns site by URL
	 *
	 * @param string $URL
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	abstract public function getByURL( $URL );

	/**
	 * Returns default site
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	abstract public function getDefault();

	/**
	 * @return array
	 */
	abstract public function getLayoutsList();
}