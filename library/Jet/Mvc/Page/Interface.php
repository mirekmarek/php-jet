<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_Page_Interface
 *
 */
interface Mvc_Page_Interface {
	/**
	 * @param Mvc_Site_Interface $site
	 */
	public function setSite( Mvc_Site_Interface $site );

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite();

	/**
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getSiteLocalizedData();


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
	public function setId($id );

	/**
	 * @return $parent
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
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getParent();

	/**
	 *
	 */
	public function sortChildren();

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
	public function setName($name);

	/**
	 * @return bool
	 */
	public function getIsActive();

	/**
	 * @param bool $is_active
	 */
	public function setIsActive($is_active);

	/**
	 * @return bool
	 */
	public function getIsAdminUI();

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI($is_admin_UI);
	
	/**
	 * @return bool
	 */
	public function getIsSecretPage();

	/**
	 * @param bool $is_secret_page
	 */
	public function setIsSecretPage($is_secret_page);

	/**
	 * @return bool
	 */
	public function getIsDirectOutput();

	/**
	 * @param bool $is_direct_output
	 */
	public function setIsDirectOutput($is_direct_output);

	/**
	 * @return string
	 */
	public function getDirectOutputFileName();

	/**
	 * @param string $direct_output_file_name
	 */
	public function setDirectOutputFileName($direct_output_file_name);


	/**
	 * @return bool
	 */
	public function getAccessAllowed();


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
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param string $title
	 */
	public function setTitle( $title);

	/**
	 * @return string
	 */
	public function getMenuTitle();

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle($menu_title);

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle();

	/**
	 * @param $breadcrumb_title
	 */
	public function setBreadcrumbTitle($breadcrumb_title);

	/**
	 * @return string
	 */
	public function getUrlFragment();

	/**
	 * @param string $URL_fragment
	 * @param bool $encode (optional, default = true)
	 */
	public function setUrlFragment( $URL_fragment, $encode=true );


    /**
     * @param array $GET_params
     * @param array $path_fragments
     * @return string
     */
    public function getURL(array $GET_params= [], array $path_fragments= []);

    /**
     * @param array $GET_params
     * @param array $path_fragments
     *
     * @return string
     */
    public function getURI(array $GET_params= [], array $path_fragments= []);

	/**
	 * Example: //domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSchemaURL(array $GET_params= [], array $path_fragments= []);

	/**
	 * Example: http://domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSslURL(array $GET_params= [], array $path_fragments= []);

	/**
	 * Example: https://domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getSslURL(array $GET_params= [], array $path_fragments= []);

    /**
     * @return string
     */
    public function getLayoutsPath();

    /**
     * @param string $layouts_dir
     */
    public function setCustomLayoutsPath($layouts_dir);

    /**
     * @return string
     */
    public function getCustomLayoutsPath();

	/**
	 * @return string
	 */
	public function getLayoutScriptName();

	/**
	 * @param string $layout
	 */
	public function setLayoutScriptName($layout);

    /**
     *
     * @throws Exception
     *
     */
    public function initializeLayout();


	/**
     * @param bool $get_default (optional)
     *
     * @return string
	 */
	public function getHeadersSuffix( $get_default=false );

	/**
	 * @param string $headers_suffix
	 */
	public function setHeadersSuffix( $headers_suffix );

	/**
     * @param bool $get_default (optional)
     *
     * @return string
	 */
	public function getBodyPrefix( $get_default=false );

	/**
	 * @param string $body_prefix
	 */
	public function setBodyPrefix( $body_prefix );

    /**
     * @param bool $get_default (optional)
     *
     * @return string
     */
	public function getBodySuffix( $get_default=false );

	/**
	 * @param string $body_suffix
	 */
	public function setBodySuffix( $body_suffix );

	
	/**
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired($SSL_required);

	/**
	 * @return array
	 */
	public function getHttpHeaders();

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers );


    /**
     * @param bool $get_default (optional)
     *
     * @return Mvc_Page_MetaTag_Interface[]
     */
	public function getMetaTags( $get_default=false );

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag);

	/**
	 * @param int $index
	 */
	public function removeMetaTag( $index );

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function  setMetaTags( $meta_tags );

	/**
	 * @return string|null
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
	public function addContent( Mvc_Page_Content_Interface $content);

	/**
	 * @param int $index
	 */
	public function removeContent( $index );

	/**
	 * @param Mvc_Page_Content_Interface[] $contents
	 */
	public function setContent($contents );



    /**
     * @return Mvc_NavigationData_Breadcrumb_Abstract[]
     */
    public function getBreadcrumbNavigation();

    /**
     * @param Mvc_NavigationData_Breadcrumb_Abstract $item
     */
    public function addBreadcrumbNavigationItem( Mvc_NavigationData_Breadcrumb_Abstract $item  );

    /**
     * @param string $title
     * @param string $URI (optional)
     */
    public function addBreadcrumbNavigationData( $title, $URI='' );

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function addBreadcrumbNavigationPage( Mvc_Page_Interface $page );


    /**
     * @param Mvc_NavigationData_Breadcrumb_Abstract[] $data
     * @throws Exception
     */
    public function setBreadcrumbNavigation( $data );

    /**
     *
     * @param int $shift_count
     */
    public function breadcrumbNavigationShift( $shift_count );

    /**
     *
     * @return bool
     */
    public function parseRequestURL();

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $relative_URI
	 * @return Mvc_Page_Interface|null
	 */
	public function getByRelativeURI( Mvc_Site_Interface $site, Locale $locale, $relative_URI );


    /**
     * @param string $file_path
     */
    public function handleFile( $file_path );

	/**
	 *
	 */
	public function handleDirectOutput();

	/**
	 *
	 */
	public function handleHttpHeaders();

    /**
     * @return string
     */
    public function render();



	/**
	 * @param Mvc_Page_Interface $page
	 *
	 */
	public static function appendPage( Mvc_Page_Interface $page );

	/**
	 *
	 * @param string $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null $site_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function get( $page_id=null, $locale=null, $site_id=null  );

	/**
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getList($site_id, Locale $locale );

}