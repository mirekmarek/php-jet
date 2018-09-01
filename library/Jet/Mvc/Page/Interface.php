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
interface Mvc_Page_Interface
{
	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @return array
	 */
	public static function loadPagesData( Mvc_Site_Interface $site, Locale $locale );

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale );

	/**
	 * @param Mvc_Site_Interface      $site
	 * @param Locale                  $locale
	 * @param array                   $data
	 * @param Mvc_Page_Interface|null $parent_page
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function createByData( Mvc_Site_Interface $site, Locale $locale, array $data, Mvc_Page_Interface $parent_page = null );

	/**
	 * @param Mvc_Page_Interface $page
	 *
	 */
	public static function appendPage( Mvc_Page_Interface $page );

	/**
	 *
	 * @param string             $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null        $site_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function get( $page_id = null, $locale = null, $site_id = null );

	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getList( $site_id, Locale $locale );


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale             $locale
	 * @param string             $relative_path
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function getByRelativePath( Mvc_Site_Interface $site, Locale $locale, $relative_path );


	/**
	 * @return string
	 */
	public function getSiteId();

	/**
	 * @param string $site_id
	 */
	public function setSiteId( $site_id );

	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site );

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite();

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale );

	/**
	 *
	 * @return Locale
	 */
	public function getLocale();

	/**
	 * @param string $id
	 */
	public function setId( $id );

	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getKey();

	/**
	 * @param Mvc_Page_Interface $parent
	 */
	public function setParent( Mvc_Page_Interface $parent );

	/**
	 * @return bool
	 */
	public function isCurrent();

	/**
	 * @return bool
	 */
	public function isInCurrentPath();

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getParent();

	/**
	 * @return array
	 */
	public function getPath();

	/**
	 * @return string
	 */
	public function getChildrenIds();

	/**
	 * @return string
	 */
	public function getChildrenKeys();

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren();

	/**
	 * @param Mvc_Page_Interface $child
	 */
	public function appendChild( Mvc_Page_Interface $child );

	/**
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
	public function getIsDeactivatedByDefault();

	/**
	 * @return bool
	 */
	public function getIsActive();

	/**
	 * @return bool
	 */
	public function isSSLRequiredByDefault();


	/**
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired( $SSL_required );


	/**
	 * @param bool $is_active
	 */
	public function setIsActive( $is_active );


	/**
	 * @param bool $is_secret
	 */
	public function setIsSecret( $is_secret );

	/**
	 * @return bool
	 */
	public function isSecretByDefault();

	/**
	 * @return bool
	 */
	public function getIsSecret();

	/**
	 * @return bool
	 */
	public function getIsSubApp();

	/**
	 * @param bool $is_sub_app
	 */
	public function setIsSubApp( $is_sub_app );

	/**
	 * @return string
	 */
	public function getSubAppIndexFileName();

	/**
	 * @param string $index_file_name
	 */
	public function setSubAppIndexFileName( $index_file_name );

	/**
	 * @return array
	 */
	public function getSubAppPhpFileExtensions();

	/**
	 * @param array $php_file_extensions
	 */
	public function setSubAppPhpFileExtensions( array $php_file_extensions );


	/**
	 * @return int
	 */
	public function getOrder();

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( $order );

	/**
	 * @param string $relative_path_fragment
	 */
	public function setRelativePathFragment( $relative_path_fragment );

	/**
	 * @return string
	 */
	public function getRelativePathFragment();


	/**
	 * @return string
	 */
	public function getRelativePath();


	/**
	 * @param string $relative_path
	 */
	public function setRelativePath( $relative_path );

	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param string $title
	 */
	public function setTitle( $title );

	/**
	 * @return string
	 */
	public function getIcon();

	/**
	 * @param string $icon
	 */
	public function setIcon( $icon );

	/**
	 * @return string
	 */
	public function getMenuTitle();

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle( $menu_title );

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle();

	/**
	 * @param string $breadcrumb_title
	 */
	public function setBreadcrumbTitle( $breadcrumb_title );

	/**
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURL( array $path_fragments = [], array $GET_params = [] );

	/**
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getURI( array $path_fragments = [], array $GET_params = [] );

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSchemaURL( array $path_fragments = [], array $GET_params = [] );

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getNonSslURL( array $path_fragments = [], array $GET_params = [] );

	/**
	 *
	 * @param array $path_fragments
	 *
	 * @param array $GET_params
	 *
	 * @return string
	 */
	public function getSslURL( array $path_fragments = [], array $GET_params = [] );

	/**
	 * @return string
	 */
	public function getLayoutsPath();

	/**
	 * @return string
	 */
	public function getLayoutScriptName();

	/**
	 * @param string $layout
	 */
	public function setLayoutScriptName( $layout );

	/**
	 *
	 * @throws Exception
	 *
	 */
	public function initializeLayout();

	/**
	 * @return array
	 */
	public function getHttpHeaders();

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers );

	/**
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags();

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag );

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function setMetaTags( $meta_tags );

	/**
	 * @param string|callable $output
	 */
	public function setOutput( $output );

	/**
	 * @return string|callable|null
	 */
	public function getOutput();

	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent();

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content );

	/**
	 * @param int $index
	 */
	public function removeContent( $index );

	/**
	 * @param Mvc_Page_Content_Interface[] $contents
	 */
	public function setContent( $contents );

	/**
	 *
	 * @return bool
	 */
	public function resolvePath();

	/**
	 * @param string $file_path
	 */
	public function handleFile( $file_path );

	/**
	 *
	 */
	public function handleSubApp();

	/**
	 *
	 */
	public function handleHttpHeaders();

	/**
	 * @return bool
	 */
	public function accessAllowed();

	/**
	 * @return string
	 */
	public function render();

}