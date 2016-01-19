<?php
/**
 *
 *
 *
 * @see Factory
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Factory
 */
namespace Jet;

class Mvc_Factory extends Factory {

	const DEFAULT_ROUTER_CLASS = 'Mvc_Router';
	const DEFAULT_ROUTER_CONFIG_CLASS = 'Mvc_Router_Config';

	/**
	 * @var string
	 */
	protected static $router_cache_backend_class_name_prefix = 'Mvc_Router_Cache_Backend_';


	const DEFAULT_PAGE_ID_CLASS = 'Mvc_Page_ID';
	const DEFAULT_PAGE_CLASS = 'Mvc_Page';
	const DEFAULT_PAGE_META_TAG_CLASS = 'Mvc_Page_MetaTag';
	const DEFAULT_PAGE_CONTENT_CLASS = 'Mvc_Page_Content';

	const DEFAULT_SITE_CLASS = 'Mvc_Site';
	const DEFAULT_LOCALIZED_SITE_CLASS = 'Mvc_Site_LocalizedData';
	const DEFAULT_LOCALIZED_SITE_META_TAG_CLASS = 'Mvc_Site_LocalizedData_MetaTag';
	const DEFAULT_LOCALIZED_SITE_URL_CLASS = 'Mvc_Site_LocalizedData_URL';

	const DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS = 'Mvc_NavigationData_Breadcrumb';

	const DEFAULT_LAYOUT_CSS_PACKAGE_CREATOR_CLASS = 'Mvc_Layout_PackageCreator_CSS';
	const DEFAULT_LAYOUT_JAVASCRIPT_PACKAGE_CREATOR_CLASS = 'Mvc_Layout_PackageCreator_JavaScript';

	/**
	 * @param string $router_cache_backend_class_name_prefix
	 */
	public static function setRouterCacheBackendClassNamePrefix($router_cache_backend_class_name_prefix) {
		static::$router_cache_backend_class_name_prefix = $router_cache_backend_class_name_prefix;
	}

	/**
	 * @return string
	 */
	public static function getRouterCacheBackendClassNamePrefix() {
		return static::$router_cache_backend_class_name_prefix;
	}



	/**
	 * @return string
	 */
	public static function getPageIDClassName() {
		return static::getClassName( static::DEFAULT_PAGE_ID_CLASS );
	}

	/**
	 * @param string $class_name
	 */
	public static function setPageIDClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_ID_CLASS, $class_name);
	}

	/**
	 * Returns instance of Page ID class @see Factory
	 *
	 * @return Mvc_Page_ID_Abstract
	 */
	public static function getPageIDInstance() {
		$class_name = static::getClassName(static::DEFAULT_PAGE_ID_CLASS);

		$definition = static::getPageInstance()->getDataModelDefinition();

		$instance = new $class_name($definition);

		return $instance;
	}

	/**
	 * @return string
	 */
	public static function getPageClassName() {
		return static::getClassName( static::DEFAULT_PAGE_CLASS );
	}

	/**
	 * @param string $class_name
	 */
	public static function setPageClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_CLASS, $class_name);
	}

	/**
	 * Returns instance of Page class @see Factory
	 *
	 * @param string $site_ID
	 * @param Locale $locale
	 * @param string $name
	 * @param string $parent_ID (optional)
	 * @param string $ID (optional)
	 *
	 * @return Mvc_Page_Abstract
	 */
	public static function getPageInstance( $site_ID='', Locale $locale=null , $name='', $parent_ID='', $ID=null ) {
		$class_name = static::getPageClassName();
		$instance = new $class_name( $site_ID, $locale , $name, $parent_ID, $ID );
		//static::checkInstance(static::DEFAULT_PAGE_CLASS, $instance);
		return $instance;
	}

	/**
	* Returns instance of ContentData class @see Factory
	*
	* @return Mvc_Page_Content_Abstract
	*/
	public static function getPageContentInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_CONTENT_CLASS );
		$instance = new $class_name();
		//static::checkInstance(static::DEFAULT_PAGE_CONTENT_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Page MetaTag class @see Factory
	 *
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 *
	 * @return Mvc_Page_MetaTag_Abstract
	 */
	public static function getPageMetaTagInstance( $content='', $attribute='', $attribute_value='' ) {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_META_TAG_CLASS );
		$instance = new $class_name( $content, $attribute, $attribute_value );
		//static::checkInstance(static::DEFAULT_PAGE_META_TAG_CLASS, $instance);
		return $instance;
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPageMetaTagClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_META_TAG_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPageContentClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_CONTENT_CLASS, $class_name);
	}


	/**
	 * @return string
	 */
	public static function getSiteClassName() {
		return static::getClassName( static::DEFAULT_SITE_CLASS );
	}

	/**
	 * Returns instance of Site class @see Factory
	 *
	 * @return Mvc_Site_ID_Abstract
	 */
	public static function getSiteIDInstance() {
		$class_name = static::getSiteClassName();

		/**
		 * @var Mvc_Site_Abstract $class_name
		 */
		return $class_name::getEmptyIDInstance();
	}

	/**
	 * Returns instance of Site class @see Factory
	 *
	 * @param string $name (optional)
	 * @param string $ID (optional)
	 *
	 * @return Mvc_Site_Abstract
	 */
	public static function getSiteInstance( $name='', $ID=null ) {
		$class_name = static::getSiteClassName();
		$instance = new $class_name( $name, $ID );
		//static::checkInstance(static::DEFAULT_SITE_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site class
	 * @see Factory
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Abstract
	 */
	public static function getLocalizedSiteInstance( Locale $locale=null ) {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_CLASS );
		$instance = new $class_name( $locale );
		//static::checkInstance(static::DEFAULT_LOCALIZED_SITE_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site MetaTag class @see Factory
	 *
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Abstract
	 */
	public static function getLocalizedSiteMetaTagInstance( $content='', $attribute='', $attribute_value='' ) {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS );
		$instance = new $class_name( $content, $attribute, $attribute_value );
		//static::checkInstance(static::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site URL class @see Factory
	 *
	 * @param string $URL (optional)
	 * @param bool $is_default (optional)
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	public static function getLocalizedSiteURLInstance( $URL='', $is_default=false ) {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_URL_CLASS );
		$instance = new $class_name( $URL, $is_default );
		//static::checkInstance(static::DEFAULT_LOCALIZED_SITE_URL_CLASS, $instance);
		return $instance;
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setSiteClass( $class_name ) {
		static::setClassName(static::DEFAULT_SITE_CLASS, $class_name);
	}

	/**
	 * @see tory
	 *
	 * @param string $class_name
	 */
	public static function setLocalizedSiteClass( $class_name ) {
		static::setClassName(static::DEFAULT_LOCALIZED_SITE_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setLocalizedSiteMetaTagClass( $class_name ) {
		static::setClassName(static::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setLocalizedSiteURLClass( $class_name ) {
		static::setClassName(static::DEFAULT_LOCALIZED_SITE_URL_CLASS, $class_name);
	}


	/**
	* Returns instance of Router class @see Factory
	*
	* @return Mvc_Router_Abstract
	*/
	public static function getRouterInstance() {
		$class_name =  static::getClassName( static::DEFAULT_ROUTER_CLASS );
		$instance = new $class_name();
		//static::checkInstance(static::DEFAULT_ROUTER_CLASS, $instance);
		return $instance;
	}



	/**
	 * Returns instance of Router configuration class
	 * @see Factory
	 *
	 * @param bool $soft_mode (optional, default:false)
	 *
	 * @return Mvc_Router_Config_Abstract
	 */
	public static function getRouterConfigInstance( $soft_mode=false ) {
		$class_name =  static::getClassName( static::DEFAULT_ROUTER_CONFIG_CLASS );
		$instance = new $class_name($soft_mode);
		//static::checkInstance(static::DEFAULT_ROUTER_CONFIG_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Router Cache Backend
	 *
	 * @see Factory
	 *
	 * @param string $backend_type
	 * @param bool $soft_mode
	 *
	 * @return Mvc_Router_Cache_Backend_Config_Abstract
	 */
	public static function getRouterCacheBackendConfigInstance( $backend_type, $soft_mode=false ) {
		$default_class_name =  static::$router_cache_backend_class_name_prefix.$backend_type.'_Config';

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name($soft_mode);
		//static::checkInstance($default_class_name, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Router Cache Backend
	 * @see Factory
	 *
	 * @param string $backend_type
	 * @param Mvc_Router_Cache_Backend_Config_Abstract $backend_config
	 *
	 * @return Mvc_Router_Cache_Backend_Abstract
	 */
	public static function getRouterCacheBackendInstance( $backend_type, Mvc_Router_Cache_Backend_Config_Abstract $backend_config ) {
		$default_class_name = static::$router_cache_backend_class_name_prefix.$backend_type;

		$class_name =  static::getClassName( $default_class_name );
		$instance = new $class_name( $backend_config );
		//static::checkInstance($default_class_name, $instance);
		return $instance;
	}


	/**
	 * Returns instance of NavigationData_Breadcrumb class @see Factory
	 *
	 * @return Mvc_NavigationData_Breadcrumb_Abstract
	 */
	public static function getNavigationDataBreadcrumbInstance() {
		$class_name =  static::getClassName( static::DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS );

		$instance = new $class_name();
		//static::checkInstance(static::DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of NavigationData_Menu class @see Factory
	 *
	 * return Mvc_NavigationData_Menu_Abstract
	 *
	public static function getNavigationDataMenuInstance() {
		$class_name =  static::getClassName( static::DEFAULT_NAVIGATION_DATA_MENU_CLASS );

		$instance = new $class_name();
		static::checkInstance($instance);
		return $instance;
	}

	/**
	 * Returns instance of NavigationData_Menu class @see Factory
	 *
	 * return Mvc_NavigationData_Menu_Abstract
	 *
	public static function getNavigationDataMenuItemInstance() {
		$class_name =  static::getClassName( static::DEFAULT_NAVIGATION_DATA_MENU_ITEM_CLASS );

		$instance = new $class_name();
		static::checkInstance($instance);
		return $instance;
	}
	*/

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setNavigationDataBreadcrumbClass( $class_name ) {
		static::setClassName(static::DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS, $class_name);
	}


	/**
	 * @param string $media
	 * @param Locale $locale
	 * @param array $URIs
	 *
	 * @return Mvc_Layout_PackageCreator_CSS_Abstract
	 */
	public static function getLayoutCssPackageCreatorInstance( $media, Locale $locale, array $URIs ) {
		$class_name =  static::getClassName( static::DEFAULT_LAYOUT_CSS_PACKAGE_CREATOR_CLASS );

		$instance = new $class_name( $media, $locale, $URIs );
		//static::checkInstance(static::DEFAULT_LAYOUT_CSS_PACKAGE_CREATOR_CLASS, $instance);
		return $instance;

	}

	/**
	 * @param string $class_name
	 */
	public static function setLayoutCssPackageCreatorClassName( $class_name ) {
		static::setClassName(static::DEFAULT_LAYOUT_CSS_PACKAGE_CREATOR_CLASS, $class_name);
	}

	/**
	 * @param Locale $locale
	 * @param array $URIs
	 * @param array $code
	 *
	 * @return Mvc_Layout_PackageCreator_JavaScript_Abstract
	 */
	public static function getLayoutJavaScriptPackageCreatorInstance( Locale $locale, array $URIs, array $code ) {
		$class_name =  static::getClassName( static::DEFAULT_LAYOUT_JAVASCRIPT_PACKAGE_CREATOR_CLASS );

		$instance = new $class_name( $locale, $URIs, $code );
		//static::checkInstance(static::DEFAULT_LAYOUT_CSS_PACKAGE_CREATOR_CLASS, $instance);
		return $instance;

	}

	/**
	 * @param string $class_name
	 */
	public static function setLayoutJavaScriptPackageCreatorClassName( $class_name ) {
		static::setClassName(static::DEFAULT_LAYOUT_JAVASCRIPT_PACKAGE_CREATOR_CLASS, $class_name);
	}

}