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
 * Class Mvc_Page_Abstract
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getPageInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Page_Abstract'
 *
 * @JetDataModel:name = 'page'
 * @JetDataModel:ID_class_name = 'Jet\Mvc_Page_ID_Abstract'
 */
abstract class Mvc_Page_Abstract extends DataModel {

	/**
	 * @param string $ID
	 */
	abstract protected function setID( $ID );

    /**
     * @param string $site_ID
     */
	abstract protected function setSiteID( $site_ID );

    /**
     * @param Locale $locale
     */
	abstract protected function setLocale( Locale $locale );

	/**
	 * @return Mvc_Site_ID_Abstract
	 */
	abstract public function getSiteID();

	/**
	 *
	 * @return Locale
	 */
	abstract public function getLocale();

    /**
     * @param string $service_type
     */
    abstract public function setServiceType($service_type);

    /**
     * @return string
     */
    abstract public function getServiceType();

    /**
     * @param boolean $is_dynamic
     */
    abstract public function setIsDynamic($is_dynamic);

    /**
     * @return boolean
     */
    abstract public function getIsDynamic();

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @param string $name
	 */
	abstract public function setName($name);

	/**
	 * @return bool
	 */
	abstract public function getIsAdminUI();

	/**
	 * @param bool $is_admin_UI
	 */
	abstract public function setIsAdminUI($is_admin_UI);

	/**
	 * @return Mvc_Site_Abstract
	 */
	abstract public function getSite();

    /**
     * @return Mvc_Site_LocalizedData_Abstract
     */
    abstract public function getSiteLocalizedData();

	/**
	 * @return string
	 */
	abstract public function getParentID();

    /**
     * @param string $parent_ID
     */
	abstract public function setParentID( $parent_ID );

	/**
	 *
	 * @return Mvc_Page_Abstract
	 */
	abstract public function getParent();

	/**
	 * @return string
	 */
	abstract public function getTitle();

	/**
	 * @param string $title
	 */
	abstract public function setTitle( $title);

	/**
	 * @return string
	 */
	abstract public function getMenuTitle();

	/**
	 * @param string $menu_title
	 */
	abstract public function setMenuTitle($menu_title);

	/**
	 * @return string
	 */
	abstract public function getBreadcrumbTitle();

	/**
	 * @param $breadcrumb_title
	 */
	abstract public function setBreadcrumbTitle($breadcrumb_title);

	/**
	 * @return string
	 */
	abstract public function getUrlFragment();

    /**
     * @param array $GET_params
     * @param array $path_fragments
     * @return string
     */
    abstract public function getURL( array $GET_params=array(), array $path_fragments=array() );

    /**
     * @param array $GET_params
     * @param array $path_fragments
     *
     * @return string
     */
    abstract public function getURI( array $GET_params=array(), array $path_fragments=array() );

	/**
	 * Example: //domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	abstract public function getNonSchemaURL( array $GET_params=array(), array $path_fragments=array() );

	/**
	 * Example: http://domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	abstract public function getNonSslURL( array $GET_params=array(), array $path_fragments=array() );

	/**
	 * Example: https://domain/page/
     *
     * @param array $GET_params
     * @param array $path_fragments
	 *
	 * @return string
	 */
	abstract public function getSslURL( array $GET_params=array(), array $path_fragments=array() );

    /**
     * @return string
     */
    abstract public function getLayoutsPath();

    /**
     * @param string $layouts_dir
     */
    abstract public function setCustomLayoutsPath($layouts_dir);

    /**
     * @return string
     */
    abstract public function getCustomLayoutsPath();

	/**
	 * @return string
	 */
	abstract public function getLayoutScriptName();

	/**
	 * @param string $layout
	 */
	abstract public function setLayoutScriptName($layout);


    /**
     * @param string $layout_initializer_module_name
     */
    abstract public function setLayoutInitializerModuleName($layout_initializer_module_name);

    /**
     * @return string
     */
    abstract public function getLayoutInitializerModuleName();

    /**
     * Setups layout. Example: turn on JetML, setup icons URL and so on.
     *
     * @throws Exception
     *
     * @return Mvc_Layout
     */
    abstract public function initializeLayout();

    /**
     *
     * @return Mvc_Layout
     */
    abstract public function getLayout();

    /**
     * @param Mvc_Layout $layout
     */
    abstract public function setLayout( Mvc_Layout $layout);


	/**
     * @param bool $get_default (optional)
     *
     * @return string
	 */
	abstract public function getHeadersSuffix( $get_default=false );

	/**
	 * @param string $headers_suffix
	 */
	abstract public function setHeadersSuffix( $headers_suffix );

	/**
     * @param bool $get_default (optional)
     *
     * @return string
	 */
	abstract public function getBodyPrefix( $get_default=false );

	/**
	 * @param string $body_prefix
	 */
	abstract public function setBodyPrefix( $body_prefix );

    /**
     * @param bool $get_default (optional)
     *
     * @return string
     */
	abstract public function getBodySuffix( $get_default=false );

	/**
	 * @param string $body_suffix
	 */
	abstract public function setBodySuffix( $body_suffix );

	/**
	 * @return bool
	 */
	abstract public function getAuthenticationRequired();

    /**
     * @return bool
     */
    abstract public function getAccessAllowed();

	/**
	 * @param bool $authentication_required
	 */
	abstract public function setAuthenticationRequired($authentication_required);

	/**
	 * @return bool
	 */
	abstract public function getSSLRequired();

	/**
	 * @param bool $SSL_required
	 */
	abstract public function setSSLRequired($SSL_required);


    /**
     * @param bool $get_default (optional)
     *
     * @return Mvc_Page_MetaTag_Abstract[]
     */
	abstract public function getMetaTags( $get_default=false );

	/**
	 * @param Mvc_Page_MetaTag_Abstract $meta_tag
	 */
	abstract public function addMetaTag( Mvc_Page_MetaTag_Abstract $meta_tag);

	/**
	 * @param int $index
	 */
	abstract public function removeMetaTag( $index );

	/**
	 * @param Mvc_Page_MetaTag_Abstract[] $meta_tags
	 */
	abstract public function  setMetaTags( $meta_tags );

	/**
	 *
	 * @return Mvc_Page_Content_Abstract[]
	 */
	abstract public function getContents();

	/**
	 * @param Mvc_Page_Content_Abstract $content
	 */
	abstract public function addContent( Mvc_Page_Content_Abstract $content);

	/**
	 * @param int $index
	 */
	abstract public function removeContent( $index );

	/**
	 * @param Mvc_Page_Content_Abstract[] $contents
	 */
	abstract public function setContents( $contents );


	/**
	 * @param string $site_ID
	 * @param Locale|string $locale (optional)
	 *
	 * @return Mvc_Page_Abstract[]
	 */
	abstract public function getList( $site_ID, $locale );

    /**
     * @param Mvc_Site_Abstract $site
     * @param Locale $locale
     * @param string $relative_URI
     * @return Mvc_Page_Abstract|null
     */
    abstract public function getByRelativeURI( Mvc_Site_Abstract $site, Locale $locale, $relative_URI );

    /**
     *
     */
    abstract public function handleDirectOutput();

	/**
	 *
	 *
	 * @return Data_Tree_Forest
	 */
	abstract public function getAllPagesTree();

	/**
	 * @return DataModel_Fetch_Object_IDs
	 */
	abstract public function getChildrenIDs();

	/**
	 * @return Mvc_Page_Abstract[]
	 */
	abstract public function getChildren();


    /**
     * @return Mvc_NavigationData_Breadcrumb_Abstract[]
     */
    abstract public function getBreadcrumbNavigation();

    /**
     * @param Mvc_NavigationData_Breadcrumb_Abstract $item
     */
    abstract public function addBreadcrumbNavigationItem( Mvc_NavigationData_Breadcrumb_Abstract $item  );

    /**
     * @param string $title
     * @param string $URI (optional)
     */
    abstract public function addBreadcrumbNavigationData( $title, $URI='' );

    /**
     * @param Mvc_Page_ID_Abstract  $page_ID (optional)
     */
    abstract public function addBreadcrumbNavigationPage( Mvc_Page_ID_Abstract $page_ID );


    /**
     * @param Mvc_NavigationData_Breadcrumb_Abstract[] $data
     * @throws Exception
     */
    abstract public function setBreadcrumbNavigation( $data );

    /**
     *
     * @param int $shift_count
     */
    abstract public function breadcrumbNavigationShift( $shift_count );

    /**
     *
     * @param Mvc_View $view
     * @param $script
     * @param string $position (optional, default:  by current dispatcher queue item, @see Mvc_Layout)
     * @param bool $position_required (optional, default: by current dispatcher queue item, @see Mvc_Layout)
     * @param int $position_order (optional, default: by current dispatcher queue item, @see Mvc_Layout)
     *
     * @internal param string $output
     */
    abstract public function renderView(
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
    abstract public function parseRequestURL();

    /**
     * @param string $auth_controller_module_name
     */
    abstract public function setAuthControllerModuleName($auth_controller_module_name);


    /**
     * @param bool $return_default (optional, default: false)
     *
     * @return string
     */
    abstract public function getAuthControllerModuleName( $return_default=false );


    /**
     * Returns Auth module instance
     *
     * @return Auth_ControllerModule_Abstract
     */
    abstract public function getAuthController();

    /**
     * @return Mvc_Page_Content_Abstract
     */
    abstract public function getCurrentContent();


    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string|null
     */
    abstract public function getOutput();

    /**
     * @param Mvc_Page_Content_Abstract $page_content
     *
     */
    abstract protected function dispatchContentItem( Mvc_Page_Content_Abstract $page_content );

    /**
     * @param Mvc_Page_Content_Abstract $page_content
     *
     * @return string
     */
    abstract public function renderContentItem( Mvc_Page_Content_Abstract $page_content );


    /**
     * @param array &$data
     */
    abstract public function readCachedData(&$data);

    /**
     * @param &$data
     */
    abstract public function writeCachedData(&$data);

}