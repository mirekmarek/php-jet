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

	const DEFAULT_ROUTER_CLASS = 'Jet\\Mvc_Router_Default';
	const DEFAULT_ROUTER_CONFIG_CLASS = 'Jet\\Mvc_Router_Config_Default';

	/**
	 * @var string
	 */
	protected static $router_cache_backend_class_name_prefix = 'Jet\\Mvc_Router_Cache_Backend_';

	const DEFAULT_DISPATCHER_CLASS = 'Jet\\Mvc_Dispatcher_Default';

	const DEFAULT_PAGE_HANDLER_CLASS = 'Jet\\Mvc_Pages_Handler_Default';
	const DEFAULT_PAGE_CLASS = 'Jet\\Mvc_Pages_Page_Default';
	const DEFAULT_PAGE_META_TAG_CLASS = 'Jet\\Mvc_Pages_Page_MetaTag_Default';
	const DEFAULT_PAGE_CONTENT_CLASS = 'Jet\\Mvc_Pages_Page_Content_Default';
	const DEFAULT_PAGE_URL_CLASS = 'Jet\\Mvc_Pages_Page_URL_Default';

	const DEFAULT_SITE_HANDLER_CLASS = 'Jet\\Mvc_Sites_Handler_Default';
	const DEFAULT_SITE_CLASS = 'Jet\\Mvc_Sites_Site_Default';
	const DEFAULT_LOCALIZED_SITE_CLASS = 'Jet\\Mvc_Sites_Site_LocalizedData_Default';
	const DEFAULT_LOCALIZED_SITE_META_TAG_CLASS = 'Jet\\Mvc_Sites_Site_LocalizedData_MetaTag_Default';
	const DEFAULT_LOCALIZED_SITE_URL_CLASS = 'Jet\\Mvc_Sites_Site_LocalizedData_URL_Default';

	const DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS = 'Jet\\Mvc_NavigationData_Breadcrumb_Default';
	const DEFAULT_NAVIGATION_DATA_MENU_CLASS = 'Jet\\Mvc_NavigationData_Menu_Default';
	const DEFAULT_NAVIGATION_DATA_MENU_ITEM_CLASS = 'Jet\\Mvc_NavigationData_Menu_Item_Default';

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
	 * Returns instance of Dispatcher class
	 * @see Factory
	 *
	 * @throws Mvc_Dispatcher_Exception
	 * @return Mvc_Dispatcher_Abstract
	 */
	public static function getDispatcherInstance() {
		$class_name =  static::getClassName( static::DEFAULT_DISPATCHER_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_DISPATCHER_CLASS, $instance);

		return $instance;
	}


	/**
	 * Returns instance of Page Handler class @see Factory
	 *
	 * @return Mvc_Pages_Handler_Abstract
	 */
	public static function getPageHandlerInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_HANDLER_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_PAGE_HANDLER_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Page ID class @see Factory
	 *
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public static function getPageIDInstance() {
		return static::getPageInstance()->getEmptyIDInstance();
	}

	/**
	 * Returns instance of Page class @see Factory
	 *
	 * @return Mvc_Pages_Page_Default
	 */
	public static function getPageInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_PAGE_CLASS, $instance);
		return $instance;
	}

	/**
	* Returns instance of ContentData class @see Factory
	*
	* @return Mvc_Pages_Page_Content_Abstract
	*/
	public static function getPageContentInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_CONTENT_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_PAGE_CONTENT_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Page MetaTag class @see Factory
	 *
	 * @return Mvc_Pages_Page_MetaTag_Abstract
	 */
	public static function getPageMetaTagInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_META_TAG_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_PAGE_META_TAG_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Page URL class @see Factory
	 *
	 * @return Mvc_Pages_Page_URL_Abstract
	 */
	public static function getPageURLInstance() {
		$class_name =  static::getClassName( static::DEFAULT_PAGE_URL_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_PAGE_URL_CLASS, $instance);
		return $instance;
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPageHandlerClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_HANDLER_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPageClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_CLASS, $class_name);
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
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setPageURLClass( $class_name ) {
		static::setClassName(static::DEFAULT_PAGE_URL_CLASS, $class_name);
	}


	/**
	 * Returns instance of Site Handler class @see Factory
	 *
	 * @return Mvc_Sites_Handler_Abstract
	 */
	public static function getSiteHandlerInstance() {
		$class_name =  static::getClassName( static::DEFAULT_SITE_HANDLER_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_SITE_HANDLER_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Site class @see Factory
	 *
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	public static function getSiteIDInstance() {
		return static::getSiteInstance()->getEmptyIDInstance();
	}


	/**
	 * Returns instance of Site class @see Factory
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public static function getSiteInstance() {
		$class_name =  static::getClassName( static::DEFAULT_SITE_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_SITE_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site class @see Factory
	 *
	 * @return Mvc_Sites_Site_LocalizedData_Abstract
	 */
	public static function getLocalizedSiteInstance() {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_LOCALIZED_SITE_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site MetaTag class @see Factory
	 *
	 * @return Mvc_Sites_Site_LocalizedData_MetaTag_Abstract
	 */
	public static function getLocalizedSiteMetaTagInstance() {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_LOCALIZED_SITE_META_TAG_CLASS, $instance);
		return $instance;
	}

	/**
	 * Returns instance of Localized Site URL class @see Factory
	 *
	 * @return Mvc_Sites_Site_LocalizedData_URL_Abstract
	 */
	public static function getLocalizedSiteURLInstance() {
		$class_name =  static::getClassName( static::DEFAULT_LOCALIZED_SITE_URL_CLASS );
		$instance = new $class_name();
		static::checkInstance(static::DEFAULT_LOCALIZED_SITE_URL_CLASS, $instance);
		return $instance;
	}


	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public static function setSiteHandlerClass( $class_name ) {
		static::setClassName(static::DEFAULT_SITE_HANDLER_CLASS, $class_name);
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
		static::checkInstance(static::DEFAULT_ROUTER_CLASS, $instance);
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
		static::checkInstance(static::DEFAULT_ROUTER_CONFIG_CLASS, $instance);
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
		static::checkInstance($default_class_name, $instance);
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
		static::checkInstance($default_class_name, $instance);
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
		static::checkInstance(static::DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS, $instance);
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
	public function setNavigationDataBreadcrumbClass( $class_name ) {
		static::setClassName(static::DEFAULT_NAVIGATION_DATA_BREADCRUMB_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public function setNavigationDataMenuClass( $class_name ) {
		static::setClassName(static::DEFAULT_NAVIGATION_DATA_MENU_CLASS, $class_name);
	}

	/**
	 * @see Factory
	 *
	 * @param string $class_name
	 */
	public function setNavigationDataMenuItemClass( $class_name ) {
		static::setClassName(static::DEFAULT_NAVIGATION_DATA_MENU_ITEM_CLASS, $class_name);
	}


}