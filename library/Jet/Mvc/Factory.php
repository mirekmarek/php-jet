<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
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
	protected static string $router_class_name = Mvc_Router::class;


	/**
	 * @var string
	 */
	protected static string $site_class_name = Mvc_Site::class;
	/**
	 * @var string
	 */
	protected static string $site_localized_class_name = Mvc_Site_LocalizedData::class;
	/**
	 * @var string
	 */
	protected static string $site_localized_meta_tag_class_name = Mvc_Site_LocalizedData_MetaTag::class;


	/**
	 * @var string
	 */
	protected static string $page_class_name = Mvc_Page::class;
	/**
	 * @var string
	 */
	protected static string $page_meta_tag_class_name = Mvc_Page_MetaTag::class;
	/**
	 * @var string
	 */
	protected static string $page_content_class_name = Mvc_Page_Content::class;

	/**
	 * @var string
	 */
	protected static string $view_class_name = Mvc_View::class;

	/**
	 * @var string
	 */
	protected static string $layout_class_name = Mvc_Layout::class;


	/**
	 * @return string
	 */
	public static function getRouterClassName(): string
	{
		return static::$router_class_name;
	}

	/**
	 * @param string $router_class_name
	 */
	public static function setRouterClassName( string $router_class_name ): void
	{
		static::$router_class_name = $router_class_name;
	}


	/**
	 *
	 * @return Mvc_Router_Interface
	 */
	public static function getRouterInstance(): Mvc_Router_Interface
	{
		$class_name = static::getRouterClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getSiteClassName(): string
	{
		return static::$site_class_name;
	}

	/**
	 * @param string $site_class_name
	 */
	public static function setSiteClassName( string $site_class_name ): void
	{
		static::$site_class_name = $site_class_name;
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getSiteInstance(): Mvc_Site_Interface
	{
		$class_name = static::getSiteClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageClassName(): string
	{
		return static::$page_class_name;
	}

	/**
	 * @param string $page_class_name
	 */
	public static function setPageClassName( string $page_class_name ): void
	{
		static::$page_class_name = $page_class_name;
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function getPageInstance(): Mvc_Page_Interface
	{
		$class_name = static::getPageClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageContentClassName(): string
	{
		return static::$page_content_class_name;
	}

	/**
	 * @param string $page_content_class_name
	 */
	public static function setPageContentClassName( string $page_content_class_name ): void
	{
		static::$page_content_class_name = $page_content_class_name;
	}

	/**
	 *
	 * @return Mvc_Page_Content_Interface
	 */
	public static function getPageContentInstance(): Mvc_Page_Content_Interface
	{
		$class_name = static::getPageContentClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getPageMetaTagClassName(): string
	{
		return static::$page_meta_tag_class_name;
	}

	/**
	 * @param string $page_meta_tag_class_name
	 */
	public static function setPageMetaTagClassName( string $page_meta_tag_class_name ): void
	{
		static::$page_meta_tag_class_name = $page_meta_tag_class_name;
	}


	/**
	 *
	 *
	 * @return Mvc_Page_MetaTag_Interface
	 */
	public static function getPageMetaTagInstance(): Mvc_Page_MetaTag_Interface
	{
		$class_name = static::getPageMetaTagClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getSiteLocalizedClassName(): string
	{
		return static::$site_localized_class_name;
	}

	/**
	 * @param string $site_localized_class_name
	 */
	public static function setSiteLocalizedClassName( string $site_localized_class_name ): void
	{
		static::$site_localized_class_name = $site_localized_class_name;
	}

	/**
	 *
	 * @param Locale|null $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public static function getSiteLocalizedInstance( Locale|null $locale = null ): Mvc_Site_LocalizedData_Interface
	{
		$class_name = static::getSiteLocalizedClassName();

		return new $class_name( $locale );
	}

	/**
	 * @return string
	 */
	public static function getSiteLocalizedMetaTagClassName(): string
	{
		return static::$site_localized_meta_tag_class_name;
	}

	/**
	 * @param string $site_localized_meta_tag_class_name
	 */
	public static function setSiteLocalizedMetaTagClassName( string $site_localized_meta_tag_class_name ): void
	{
		static::$site_localized_meta_tag_class_name = $site_localized_meta_tag_class_name;
	}

	/**
	 *
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface
	 */
	public static function getSiteLocalizedMetaTagInstance(): Mvc_Site_LocalizedData_MetaTag_Interface
	{
		$class_name = static::getSiteLocalizedMetaTagClassName();

		return new $class_name();
	}


	/**
	 * @return string
	 */
	public static function getViewClassName(): string
	{
		return static::$view_class_name;
	}

	/**
	 * @param string $view_class_name
	 */
	public static function setViewClassName( string $view_class_name ): void
	{
		static::$view_class_name = $view_class_name;
	}

	/**
	 * @param string $scripts_dir
	 *
	 * @return Mvc_View
	 */
	public static function getViewInstance( string $scripts_dir ): Mvc_View
	{
		$class_name = static::getViewClassName();

		return new $class_name( $scripts_dir );
	}

	/**
	 * @return string
	 */
	public static function getLayoutClassName(): string
	{
		return static::$layout_class_name;
	}

	/**
	 * @param string $layout_class_name
	 */
	public static function setLayoutClassName( string $layout_class_name ): void
	{
		static::$layout_class_name = $layout_class_name;
	}

	/**
	 * @param string $scripts_dir
	 * @param string $script_name
	 *
	 * @return Mvc_Layout
	 */
	public static function getLayoutInstance( string $scripts_dir, string $script_name ): Mvc_Layout
	{
		$class_name = static::getLayoutClassName();

		return new $class_name( $scripts_dir, $script_name );
	}


}