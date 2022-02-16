<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Factory_MVC
{

	/**
	 * @var string
	 */
	protected static string $router_class_name = MVC_Router::class;


	/**
	 * @var string
	 */
	protected static string $base_class_name = MVC_Base::class;
	/**
	 * @var string
	 */
	protected static string $base_localized_class_name = MVC_Base_LocalizedData::class;
	/**
	 * @var string
	 */
	protected static string $base_localized_meta_tag_class_name = MVC_Base_LocalizedData_MetaTag::class;


	/**
	 * @var string
	 */
	protected static string $page_class_name = MVC_Page::class;
	/**
	 * @var string
	 */
	protected static string $page_meta_tag_class_name = MVC_Page_MetaTag::class;
	/**
	 * @var string
	 */
	protected static string $page_content_class_name = MVC_Page_Content::class;

	/**
	 * @var string
	 */
	protected static string $view_class_name = MVC_View::class;

	/**
	 * @var string
	 */
	protected static string $layout_class_name = MVC_Layout::class;


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
	 * @return MVC_Router_Interface
	 */
	public static function getRouterInstance(): MVC_Router_Interface
	{
		$class_name = static::getRouterClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getBaseClassName(): string
	{
		return static::$base_class_name;
	}

	/**
	 * @param string $base_class_name
	 */
	public static function setBaseClassName( string $base_class_name ): void
	{
		static::$base_class_name = $base_class_name;
	}

	/**
	 *
	 * @return MVC_Base_Interface
	 */
	public static function getBaseInstance(): MVC_Base_Interface
	{
		$class_name = static::getBaseClassName();

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
	 * @return MVC_Page_Interface
	 */
	public static function getPageInstance(): MVC_Page_Interface
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
	 * @return MVC_Page_Content_Interface
	 */
	public static function getPageContentInstance(): MVC_Page_Content_Interface
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
	 * @return MVC_Page_MetaTag_Interface
	 */
	public static function getPageMetaTagInstance(): MVC_Page_MetaTag_Interface
	{
		$class_name = static::getPageMetaTagClassName();

		return new $class_name();
	}

	/**
	 * @return string
	 */
	public static function getBaseLocalizedClassName(): string
	{
		return static::$base_localized_class_name;
	}

	/**
	 * @param string $base_localized_class_name
	 */
	public static function setBaseLocalizedClassName( string $base_localized_class_name ): void
	{
		static::$base_localized_class_name = $base_localized_class_name;
	}

	/**
	 *
	 * @param Locale|null $locale
	 *
	 * @return MVC_Base_LocalizedData_Interface
	 */
	public static function getBaseLocalizedInstance( Locale|null $locale = null ): MVC_Base_LocalizedData_Interface
	{
		$class_name = static::getBaseLocalizedClassName();

		return new $class_name( $locale );
	}

	/**
	 * @return string
	 */
	public static function getBaseLocalizedMetaTagClassName(): string
	{
		return static::$base_localized_meta_tag_class_name;
	}

	/**
	 * @param string $base_localized_meta_tag_class_name
	 */
	public static function setBaseLocalizedMetaTagClassName( string $base_localized_meta_tag_class_name ): void
	{
		static::$base_localized_meta_tag_class_name = $base_localized_meta_tag_class_name;
	}

	/**
	 *
	 *
	 * @return MVC_Base_LocalizedData_MetaTag_Interface
	 */
	public static function getBaseLocalizedMetaTagInstance(): MVC_Base_LocalizedData_MetaTag_Interface
	{
		$class_name = static::getBaseLocalizedMetaTagClassName();

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
	 * @return MVC_View
	 */
	public static function getViewInstance( string $scripts_dir ): MVC_View
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
	 * @return MVC_Layout
	 */
	public static function getLayoutInstance( string $scripts_dir, string $script_name ): MVC_Layout
	{
		$class_name = static::getLayoutClassName();

		return new $class_name( $scripts_dir, $script_name );
	}
}
