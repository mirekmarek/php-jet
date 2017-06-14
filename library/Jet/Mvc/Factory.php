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
class Mvc_Factory
{

	/**
	 * @var string
	 */
	protected static $router_class_name = __NAMESPACE__.'\Mvc_Router';


	/**
	 * @var string
	 */
	protected static $site_class_name = __NAMESPACE__.'\Mvc_Site';
	/**
	 * @var string
	 */
	protected static $site_localized_class_name = __NAMESPACE__.'\Mvc_Site_LocalizedData';
	/**
	 * @var string
	 */
	protected static $site_localized_meta_tag_class_name = __NAMESPACE__.'\Mvc_Site_LocalizedData_MetaTag';



	/**
	 * @var string
	 */
	protected static $page_class_name = __NAMESPACE__.'\Mvc_Page';
	/**
	 * @var string
	 */
	protected static $page_meta_tag_class_name = __NAMESPACE__.'\Mvc_Page_MetaTag';
	/**
	 * @var string
	 */
	protected static $page_content_class_name = __NAMESPACE__.'\Mvc_Page_Content';

	/**
	 * @var string
	 */
	protected static $view_class_name = __NAMESPACE__.'\Mvc_View';

	/**
	 * @var string
	 */
	protected static $layout_class_name = __NAMESPACE__.'\Mvc_Layout';


	/**
	 * @return string
	 */
	public static function getRouterClassName()
	{
		return static::$router_class_name;
	}

	/**
	 * @param string $router_class_name
	 */
	public static function setRouterClassName( $router_class_name )
	{
		static::$router_class_name = $router_class_name;
	}


	/**
	 *
	 * @return Mvc_Router_Interface
	 */
	public static function getRouterInstance()
	{
		$class_name = static::getRouterClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getSiteClassName()
	{
		return static::$site_class_name;
	}

	/**
	 * @param string $site_class_name
	 */
	public static function setSiteClassName( $site_class_name )
	{
		static::$site_class_name = $site_class_name;
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getSiteInstance()
	{
		$class_name = static::getSiteClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageClassName()
	{
		return static::$page_class_name;
	}

	/**
	 * @param string $page_class_name
	 */
	public static function setPageClassName( $page_class_name )
	{
		static::$page_class_name = $page_class_name;
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function getPageInstance()
	{
		$class_name = static::getPageClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageContentClassName()
	{
		return static::$page_content_class_name;
	}

	/**
	 * @param string $page_content_class_name
	 */
	public static function setPageContentClassName( $page_content_class_name )
	{
		static::$page_content_class_name = $page_content_class_name;
	}

	/**
	 *
	 * @return Mvc_Page_Content_Interface
	 */
	public static function getPageContentInstance()
	{
		$class_name = static::getPageContentClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageMetaTagClassName()
	{
		return static::$page_meta_tag_class_name;
	}

	/**
	 * @param string $page_meta_tag_class_name
	 */
	public static function setPageMetaTagClassName( $page_meta_tag_class_name )
	{
		static::$page_meta_tag_class_name = $page_meta_tag_class_name;
	}


	/**
	 *
	 *
	 * @return Mvc_Page_MetaTag_Interface
	 */
	public static function getPageMetaTagInstance()
	{
		$class_name = static::getPageMetaTagClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getSiteLocalizedClassName()
	{
		return static::$site_localized_class_name;
	}

	/**
	 * @param string $site_localized_class_name
	 */
	public static function setSiteLocalizedClassName( $site_localized_class_name )
	{
		static::$site_localized_class_name = $site_localized_class_name;
	}

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public static function getSiteLocalizedInstance( Locale $locale = null )
	{
		$class_name = static::getSiteLocalizedClassName();

		return new $class_name( $locale );
	}

	/**
	 * @return string
	 */
	public static function getSiteLocalizedMetaTagClassName()
	{
		return static::$site_localized_meta_tag_class_name;
	}

	/**
	 * @param string $site_localized_meta_tag_class_name
	 */
	public static function setSiteLocalizedMetaTagClassName( $site_localized_meta_tag_class_name )
	{
		static::$site_localized_meta_tag_class_name = $site_localized_meta_tag_class_name;
	}

	/**
	 *
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface
	 */
	public static function getSiteLocalizedMetaTagInstance()
	{
		$class_name = static::getSiteLocalizedMetaTagClassName();

		return new $class_name();
	}



	/**
	 * @return string
	 */
	public static function getViewClassName()
	{
		return static::$view_class_name;
	}

	/**
	 * @param string $view_class_name
	 */
	public static function setViewClassName( $view_class_name )
	{
		static::$view_class_name = $view_class_name;
	}

	/**
	 * @param string $scripts_dir
	 *
	 * @return Mvc_View
	 */
	public static function getViewInstance( $scripts_dir )
	{
		$class_name = static::getViewClassName();

		return new $class_name( $scripts_dir );
	}

	/**
	 * @return string
	 */
	public static function getLayoutClassName()
	{
		return static::$layout_class_name;
	}

	/**
	 * @param string $layout_class_name
	 */
	public static function setLayoutClassName( $layout_class_name )
	{
		static::$layout_class_name = $layout_class_name;
	}

	/**
	 * @param string $scripts_dir
	 * @param string $script_name
	 *
	 * @return Mvc_Layout
	 */
	public static function getLayoutInstance( $scripts_dir, $script_name )
	{
		$class_name = static::getLayoutClassName();

		return new $class_name( $scripts_dir, $script_name );
	}


}