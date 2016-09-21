<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Page_Interface
 *
 */
interface Mvc_Page_Interface {

	/**
	 * @return string
	 */
	public function getPageKey();

	/**
	 * @param string $ID
	 */
	public function setPageId( $ID );

	/**
	 * @return string
	 */
	public function getPageId();

    /**
     * @param string $site_ID
     */
	public function setSiteId( $site_ID );

	/**
	 * @return string
	 */
	public function getSiteId();

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
     * @param string $service_type
     */
    public function setServiceType($service_type);

    /**
     * @return string
     */
    public function getServiceType();

    /**
     * @param boolean $is_dynamic
     */
    public function setIsDynamic($is_dynamic);

    /**
     * @return boolean
     */
    public function getIsDynamic();

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
	public function getIsAdminUI();

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI($is_admin_UI);

	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite();

    /**
     * @return Mvc_Site_LocalizedData_Interface
     */
    public function getSiteLocalizedData();

	/**
	 * @return string
	 */
	public function getParentId();

    /**
     * @param string $parent_ID
     */
	public function setParentId( $parent_ID );

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
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getParent();

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
	 */
	public function setUrlFragment( $URL_fragment );


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
     * @param string $layout_initializer_module_name
     */
    public function setLayoutInitializerModuleName($layout_initializer_module_name);

    /**
     * @return string
     */
    public function getLayoutInitializerModuleName();

    /**
     * Setups layout. Example: turn on JetML, setup icons URL and so on.
     *
     * @throws Exception
     *
     * @return Mvc_Layout
     */
    public function initializeLayout();

    /**
     *
     * @return Mvc_Layout
     */
    public function getLayout();

    /**
     * @param Mvc_Layout $layout
     */
    public function setLayout( Mvc_Layout $layout);


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
	public function getAuthenticationRequired();

    /**
     * @return bool
     */
    public function getAccessAllowed();

	/**
	 * @param bool $authentication_required
	 */
	public function setAuthenticationRequired($authentication_required);

	/**
	 * @return bool
	 */
	public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired($SSL_required);


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
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContents();

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
	public function setContents( $contents );


	/**
	 * @param string $site_ID
	 * @param Locale|string $locale (optional)
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public function getList( $site_ID, $locale );

    /**
     * @param Mvc_Site_Interface $site
     * @param Locale $locale
     * @param string $relative_URI
     * @return Mvc_Page_Interface|null
     */
    public function getByRelativeURI( Mvc_Site_Interface $site, Locale $locale, $relative_URI );

    /**
     *
     */
    public function handleDirectOutput();

	/**
	 *
	 *
	 * @return Data_Tree_Forest
	 */
	public function getAllPagesTree();

	/**
	 *
	 */
	public function sortChildren();

	/**
	 * @return string
	 */
	public function getChildrenIDs();

	/**
	 * @return string
	 */
	public function getChildrenKeys();

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren();


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
     * @param Mvc_View $view
     * @param $script
     * @param string $position (optional, default:  by current dispatcher queue item, @see Mvc_Layout)
     * @param bool $position_required (optional, default: by current dispatcher queue item, @see Mvc_Layout)
     * @param int $position_order (optional, default: by current dispatcher queue item, @see Mvc_Layout)
     *
     */
    public function renderView(
        Mvc_View $view,
        $script,
        $position = null,
        $position_required = null,
        $position_order = null
    );


    /**
     *
     * @return bool
     */
    public function parseRequestURL();


    /**
     * @param string $file_path
     */
    public function handleFile( $file_path );


    /**
     * @param string $auth_controller_module_name
     */
    public function setAuthControllerModuleName($auth_controller_module_name);


    /**
     *
     * @return string
     */
    public function getAuthControllerModuleName();


    /**
     * @return Mvc_Page_Content_Interface
     */
    public function getCurrentContent();


    /**
     * @return string
     */
    public function render();

    /**
     * @return string|null
     */
    public function getOutput();

    /**
     * @param array &$data
     */
    public function readCachedData(&$data);

    /**
     * @param &$data
     */
    public function writeCachedData(&$data);

}