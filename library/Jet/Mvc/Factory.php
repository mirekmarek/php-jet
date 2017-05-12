<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Factory
 * @package Jet
 */
class Mvc_Factory
{


	/**
	 *
	 * @return Mvc_Router_Interface
	 */
	public static function getRouterInstance()
	{
		$class_name = JET_MVC_ROUTER_CLASS;

		return new $class_name();
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public static function getSiteInstance()
	{
		$class_name = JET_MVC_SITE_CLASS;

		return new $class_name();
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function getPageInstance()
	{
		$class_name = JET_MVC_PAGE_CLASS;

		return new $class_name();
	}

	/**
	 *
	 * @return Mvc_Page_Content_Interface
	 */
	public static function getPageContentInstance()
	{
		$class_name = JET_MVC_PAGE_CONTENT_CLASS;

		return new $class_name();
	}

	/**
	 *
	 *
	 * @return Mvc_Page_MetaTag_Interface
	 */
	public static function getPageMetaTagInstance()
	{
		$class_name = JET_MVC_PAGE_META_TAG_CLASS;

		return new $class_name();
	}

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public static function getLocalizedSiteInstance( Locale $locale = null )
	{
		$class_name = JET_MVC_SITE_LOCALIZED_CLASS;

		return new $class_name( $locale );
	}

	/**
	 *
	 * @param string $content (optional)
	 * @param string $attribute (optional)
	 * @param string $attribute_value (optional)
	 *
	 * @return Mvc_Site_LocalizedData_MetaTag_Interface
	 */
	public static function getSiteLocalizedMetaTagInstance( $content = '', $attribute = '', $attribute_value = '' )
	{
		$class_name = JET_MVC_SITE_LOCALIZED_META_TAG_CLASS;

		return new $class_name( $content, $attribute, $attribute_value );
	}

	/**
	 *
	 * @param string $URL (optional)
	 * @param bool   $is_default (optional)
	 *
	 * @return Mvc_Site_LocalizedData_URL_Interface
	 */
	public static function getSiteLocalizedURLInstance( $URL = '', $is_default = false )
	{
		$class_name = JET_MVC_SITE_LOCALIZED_URL_CLASS;

		return new $class_name( $URL, $is_default );
	}

	/**
	 * @param string $media
	 * @param Locale $locale
	 * @param array  $URIs
	 *
	 * @return Mvc_Layout_PackageCreator_CSS
	 */
	public static function getLayoutCssPackageCreatorInstance( $media, Locale $locale, array $URIs )
	{
		$class_name = JET_MVC_LAYOUT_CSS_PACKAGE_CREATOR_CLASS;

		return new $class_name( $media, $locale, $URIs );
	}


	/**
	 * @param Locale $locale
	 * @param array  $URIs
	 * @param array  $code
	 *
	 * @return Mvc_Layout_PackageCreator_JavaScript
	 */
	public static function getLayoutJavaScriptPackageCreatorInstance( Locale $locale, array $URIs, array $code )
	{
		$class_name = JET_MVC_LAYOUT_JAVASCRIPT_PACKAGE_CREATOR_CLASS;

		return new $class_name( $locale, $URIs, $code );

	}

}