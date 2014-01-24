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
 * Class Mvc_Pages_Page_Abstract
 *
 * @JetFactory:class = 'Jet\\Mvc_Factory'
 * @JetFactory:method = 'getPageInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Mvc_Pages_Page_Abstract'
 */
abstract class Mvc_Pages_Page_Abstract extends DataModel {

	/**
	 * @var bool
	 */
	protected $_page_data_checking_mode = false;

	/**
	 * @var &array()
	 */
	protected $_page_data_checking_map;


	/**
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	abstract public function getSiteID();

	/**
	 *
	 * @return Locale
	 */
	abstract public function getLocale();

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
	 * @return string
	 */
	abstract public function getForceUIManagerModuleName();

	/**
	 * @param string $force_UI_manager_module_name
	 */
	abstract public function setForceUIManagerModuleName( $force_UI_manager_module_name );

	/**
	 * @return Mvc_Sites_Site_Abstract
	 */
	abstract public function getSite();

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
	 * @return Mvc_Pages_Page_Abstract
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
	 * Example: http://domain.tld/parent-page/this-is-url-fragment/
	 *
	 * @param $URL_fragment
	 */
	abstract public function setURLFragment( $URL_fragment );

	/**
	 * @param string $URI_fragment
	 *
	 * @param callable $exists_check
	 * @param string $suffix (optional) example: .html
	 * @param bool $remove_accents (optional, default: false)
	 *
	 * @return string
	 */
	abstract public function generateUrlFragment( $URI_fragment, callable $exists_check, $suffix='', $remove_accents=false );

	/**
	 *
	 * @param string $URL_fragment
	 *
	 * @return bool
	 */
	abstract public function getUrlFragmentExists( $URL_fragment );


	/**
	 * @return string
	 */
	abstract public function getUrlFragment();

	/**
	 * @return string
	 */
	abstract public function getURI();

	/**
	 * Example: //domain/page/
	 *
	 * @return string
	 */
	abstract public function getNonSchemaURL();

	/**
	 * Example: http://domain/page/
	 *
	 * @return string
	 */
	abstract public function getNonSslURL();

	/**
	 * Example: http://domain/page/
	 *
	 * @return string
	 */
	abstract public function getSslURL();

	/**
	 * @return string
	 */
	abstract public function getLayout();

	/**
	 * @param string $layout
	 */
	abstract public function setLayout($layout);

	/**
	 * @return string
	 */
	abstract public function getHeadersSuffix();

	/**
	 * @param string $headers_suffix
	 */
	abstract public function setHeadersSuffix( $headers_suffix );

	/**
	 * @return string
	 */
	abstract public function getBodyPrefix();

	/**
	 * @param string $body_prefix
	 */
	abstract public function setBodyPrefix( $body_prefix );

	/**
	 * @return string
	 */
	abstract public function getBodySuffix();

	/**
	 * @param string $body_suffix
	 */
	abstract public function setBodySuffix( $body_suffix );

	/**
	 * @return bool
	 */
	abstract public function getAuthenticationRequired();

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
	 * @return Mvc_Pages_Page_MetaTag_Abstract[]
	 */
	abstract public function getMetaTags();

	/**
	 * @param Mvc_Pages_Page_MetaTag_Abstract $meta_tag
	 */
	abstract public function addMetaTag( Mvc_Pages_Page_MetaTag_Abstract $meta_tag);

	/**
	 * @param int $index
	 */
	abstract public function removeMetaTag( $index );

	/**
	 * @param Mvc_Pages_Page_MetaTag_Abstract[] $meta_tags
	 */
	abstract public function  setMetaTags( $meta_tags );

	/**
	 *
	 * @return Mvc_Pages_Page_Content_Abstract[]
	 */
	abstract public function getContents();

	/**
	 * @param Mvc_Pages_Page_Content_Abstract $content
	 */
	abstract public function addContent( Mvc_Pages_Page_Content_Abstract $content);

	/**
	 * @param int $index
	 */
	abstract public function removeContent( $index );

	/**
	 * @param Mvc_Pages_Page_Content_Abstract[] $contents
	 */
	abstract public function setContents( $contents );

	/**
	 *
	 * @return Mvc_Dispatcher_Queue
	 */
	abstract public function getDispatchQueue();


	/**
	 * @param string $site_ID
	 * @param Locale $locale (optional)
	 *
	 * @return DataModel_Fetch_Object_IDs
	 */
	abstract public function getIDs(  $site_ID, $locale=null );

	/**
	 * @param string $site_ID
	 * @param Locale|string $locale (optional)
	 *
	 * @return Mvc_Pages_Page_Abstract[]
	 */
	abstract public function getList( $site_ID, $locale=null );

	/**
	 * @param string $URL
	 * @return Mvc_Pages_Page_Abstract|null
	 */
	abstract public function getByURL( $URL );

	/**
	 * @param $site_ID
	 * @param Locale $locale
	 * @param bool|null $admin_UI (optional, default: null) null = get all pages, true=only admin UI, false = all but admin UI
	 * @param array $load_properties
	 *
	 * @return Data_Tree
	 */
	abstract public function getTree( $site_ID, Locale $locale, $admin_UI = null,  $load_properties=array('name'=>'this.name')  );

	/**
	 *
	 * @param bool|null $admin_UI (optional, default: null) null = get all pages, true=only admin UI, false = all but admin UI
	 * @param array $only_properties
	 *
	 * @internal param bool $exclude_admin_UI
	 * @return Data_Tree_Forest
	 */
	abstract public function getAllPagesTree( $admin_UI = null,  $only_properties=array('name') );

	/**
	 * @return DataModel_Fetch_Object_IDs
	 */
	abstract public function getChildrenIDs();

	/**
	 * @return Mvc_Pages_Page_Abstract[]
	 */
	abstract public function getChildren();


	/**
	 * @param  array &$page_data_checking_map
	 */
	public function setPageDataCheckingMap( &$page_data_checking_map ) {
		$this->_page_data_checking_map = &$page_data_checking_map;
	}

	/**
	 * @return
	 */
	public function getPageDataCheckingMap() {
		return $this->_page_data_checking_map;
	}

	/**
	 * @param boolean $page_data_checking_mode
	 */
	public function setPageDataCheckingMode( $page_data_checking_mode ) {
		$this->_page_data_checking_mode = $page_data_checking_mode;
	}

	/**
	 * @return boolean
	 */
	public function getPageDataCheckingMode() {
		return $this->_page_data_checking_mode;
	}


}