<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 *
 * @JetDataModel:name = 'page'
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages'
 */
class Mvc_Page extends BaseObject implements Mvc_Page_Interface {
	const HOMEPAGE_ID = '_homepage_';

	const PAGE_DATA_FILE_NAME = 'page_data.php';
	const DEFAULT_DIRECT_INDEX_FILE_NAME = 'index.php';

	/**
	 * @var array
	 */
	protected static $php_file_extensions = [ 'php', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7'];

	/**
	 * @var array
	 */
	protected static $do_not_inherit_properties = [
		'breadcrumb_title',
		'menu_title',
		'order',
		'is_direct_output',
		'direct_output_file_name',
		'output'
	];

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $page_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $site_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_key = true
	 * @JetDataModel:form_field_label = 'Parent page'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $parent_id = '';

	/**
	 * @var Mvc_Page
	 */
	protected $_parent;

	/**
	 * @var bool
	 */
	protected $_children_sorted = false;

	/**
	 * @var Mvc_Page[]
	 */
	protected $_children = [];


	/**
	 * @JetDataModel:type = DataModel::TYPE_INT
	 *
	 * @JetDataModel:form_field_label = 'Order'
	 *
	 * @var int
	 */
	protected $order = 0;

	/**
	 * @var string
	 */
	protected $data_file_path = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Name'
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Is active'
	 *
	 * @var bool
	 */
	protected $is_active = true;


	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Is direct output'
	 *
	 * @var bool
	 */
	protected $is_direct_output = false;

	/**
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Direct output file name'
	 *
	 * @var string
	 */
	protected $direct_output_file_name = self::DEFAULT_DIRECT_INDEX_FILE_NAME;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Secure connection required'
	 *
	 * @var bool
	 */
	protected $SSL_required = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Is admin UI'
	 *
	 * @var bool
	 */
	protected $is_admin_UI = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_label = 'Authentication required'
	 *
	 * @var bool
	 */
	protected $is_secret_page = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title'
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title for menu item'
	 *
	 * @var string
	 */
	protected $menu_title = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Title for breadcrumb navigation'
	 *
	 * @var string
	 */
	protected $breadcrumb_title = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'URL fragment'
	 *
	 * @var string
	 */
	protected $URL_fragment = '';

	/**
	 *
	 * @var string
	 */
	protected $relative_URI = '';

	/**
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:form_field_is_required = false
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Custom layouts dir'
	 *
	 * @var string
	 */
	protected $custom_layouts_path = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Layout'
	 *
	 * @var string
	 */
	protected $layout_script_name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Headers suffix'
	 *
	 * @var string
	 */
	protected $headers_suffix = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Body prefix'
	 *
	 * @var string
	 */
	protected $body_prefix = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65535
	 * @JetDataModel:form_field_label = 'Body suffix'
	 *
	 * @var string
	 */
	protected $body_suffix = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = JET_MVC_PAGE_META_TAG_CLASS
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Mvc_Page_MetaTag[]
	 */
	protected $meta_tags = [];

	/**
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
	 * @JetDataModel:form_field_type = false
	 *
	 * @var array
	 */
	protected $http_headers = [];

	/**
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 * @JetDataModel:form_field_label = 'Auth controller'
	 *
	 * @var string
	 */
	protected $auth_controller_module_name = '';

	/**
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 9999999
	 *
	 * @var string
	 */
	protected $output;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = JET_MVC_PAGE_CONTENT_CLASS
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Mvc_Page_Content_Interface[]
	 */
	protected $content;

	/**
	 *
	 * @var Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	protected $breadcrumb_navigation = [];

	/**
	 * @var bool
	 */
	protected static $site_pages_loaded_flag = [];

	/**
	 * @var Mvc_Page_Interface[]
	 */
	protected static $loaded_pages = [];

	/**
	 * @var string[]
	 */
	protected static $relative_URIs_map = [];



	/**
	 * Returns a list of site pages
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public static function getPagesList( $site_id, Locale $locale ) {
		$page = Mvc_Factory::getPageInstance();
		return $page->getList( $site_id, $locale );
	}


	/**
	 * Return site page data object (or null if does not exist)
	 *
	 * @see Mvc_Page_Abstract
	 * @see Mvc_Page_Factory
	 *
	 * @param string $page_id (optional, null = current)
	 * @param string|Locale|null $locale (optional, null = current)
	 * @param string|null $site_id (optional, null = current)
	 *
	 * @return Mvc_Page_Interface
	 */
	public static function get( $page_id=null, $locale=null, $site_id=null  ) {
		if(!$page_id && !$locale && !$site_id) {
			return Mvc::getCurrentPage();
		}

        if(!$page_id) {
            $page_id = Mvc::getCurrentPage()->getPageId();
        }

		if(!$locale) {
			$locale = Mvc::getCurrentLocale();
		}
		if(!$site_id) {
			$site_id = Mvc::getCurrentSite()->getSiteId();
		}

		return static::_load( $site_id, $locale, $page_id );
	}

	/**
	 * @param string $data_file_path
	 */
	public function setDataFilePath($data_file_path)
	{
		$this->data_file_path = $data_file_path;
	}

	/**
	 * @return string
	 */
	public function getDataFilePath()
	{
		return $this->data_file_path;
	}

	/**
	 * @return string
	 */
	public function getDataDirPath() {
		return dirname($this->getDataFilePath()).'/';
	}

	/**
	 * @return string
	 */
	public function getPageKey() {
		return $this->site_id.':'.$this->locale.':'.$this->page_id;
	}

	/**
	 * @param string $id
	 */
	public function setPageId( $id ) {
		$this->page_id = $id;
	}

	/**
	 * @return string
	 */
	public function getPageId() {
		return $this->page_id;
	}

	/**
	 * @param string $site_id
	 */
	public function setSiteId($site_id ) {
		$this->site_id = $site_id;
	}

	/**
	 * @return string
	 */
	public function getSiteId() {
		return $this->site_id;
	}

	/**
	 * @param Locale $locale
	 *
	 */
	public function setLocale( Locale $locale ) {
		$this->locale = $locale;
	}

	/**
	 *
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function getIsActive()
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive($is_active)
	{
		$this->is_active = $is_active;
	}

	/**
	 * @return bool
	 */
	public function getIsAdminUI() {
		return $this->is_admin_UI;
	}

	/**
	 * @param bool $is_admin_UI
	 */
	public function setIsAdminUI($is_admin_UI) {
		$this->is_admin_UI = (bool)$is_admin_UI;
	}

	/**
	 * @return bool
	 */
	public function getIsSecretPage() {
		return $this->is_secret_page;
	}

	/**
	 * @param bool $is_secret_page
	 */
	public function setIsSecretPage($is_secret_page) {
		$this->is_secret_page = (bool)$is_secret_page;
	}

	/**
	 * @return bool
	 */
	public function getIsDirectOutput()
	{
		return $this->is_direct_output;
	}

	/**
	 * @param bool $is_direct_output
	 */
	public function setIsDirectOutput($is_direct_output)
	{
		$this->is_direct_output = $is_direct_output;
	}

	/**
	 * @return string
	 */
	public function getDirectOutputFileName()
	{
		return $this->direct_output_file_name;
	}

	/**
	 * @param string $direct_output_file_name
	 */
	public function setDirectOutputFileName($direct_output_file_name)
	{
		$this->direct_output_file_name = $direct_output_file_name;
	}

	/**
	 * @return bool
	 */
	public function getAccessAllowed() {
		if(
			!$this->getIsSecretPage() &&
			!$this->getIsAdminUI()
		) {
			return true;
		}

		if( Auth::getCurrentUserHasPrivilege( Auth_Role::PRIVILEGE_VISIT_PAGE, $this->getPageKey() ) ) {
			return true;
		}

		return false;

	}


	/**
	 * @return Mvc_Site_Interface
	 */
	public function getSite() {
		return Mvc_Site::get( $this->site_id );
	}

	/**
	 * @return Mvc_Site_LocalizedData_Interface
	 */
	public function getSiteLocalizedData() {
		return $this->getSite()->getLocalizedData($this->getLocale());
	}

	/**
	 * @return string
	 */
	public function getParentId() {
		return $this->parent_id;
	}

	/**
	 * @param string $parent_id
	 */
	public function setParentId($parent_id ) {
		if($this->parent_id==$parent_id) {
			return;
		}

		/**
		 * @var Mvc_Page $old_parent
		 */
		$old_parent = $this->_parent;
		if($old_parent) {
			foreach( $old_parent->_children as $i=>$ch ) {
				/**
				 * @var Mvc_Page $ch
				 */
				if($ch->page_id==$this->page_id ) {
					unset($this->_children[$i]);

					break;
				}
			}
		}

		$this->parent_id = $parent_id;

		/**
		 * @var Mvc_Page $parent
		 */
		$parent = static::get( $this->page_id, $this->locale, $this->site_id );

		if($parent) {
			$this->_parent = $parent;
			$this->_parent->_children[] = $this;
		}

	}

	/**
	 *
	 * @return Mvc_Page
	 */
	public function getParent() {
		return $this->_parent;
	}


	/**
	 * @return int
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @param int $order
	 *
	 */
	public function setOrder( $order ) {
		$this->order = (int)$order;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle( $title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getMenuTitle() {
		return $this->menu_title;
	}

	/**
	 * @param string $menu_title
	 */
	public function setMenuTitle($menu_title) {
		$this->menu_title = $menu_title;
	}

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle() {
		return $this->breadcrumb_title;
	}

	/**
	 * @param $breadcrumb_title
	 */
	public function setBreadcrumbTitle($breadcrumb_title) {
		$this->breadcrumb_title = $breadcrumb_title;
	}

	/**
	 * @param string $URL_fragment
	 */
	public function setUrlFragment( $URL_fragment ) {

		$this->URL_fragment = rawurlencode($URL_fragment);

		if( ($parent=$this->getParent()) ) {
			$this->setRelativeUrl($parent->getRelativeUrl().$this->URL_fragment.'/');
		}
	}

	/**
	 * @return string
	 */
	public function getUrlFragment() {
		return $this->URL_fragment;
	}

	/**
	 * @return string
	 */
	protected  function getRelativeUrl() {
		return $this->relative_URI;
	}

	/**
	 * @param string $URI
	 */
	protected function setRelativeUrl( $URI ) {
		$this->relative_URI = $URI;
	}

	/**
	 * @param $base_URL
	 * @param array $GET_params
	 * @param array $path_fragments
	 * @return string
	 */
	protected function _createURL( $base_URL, array $GET_params, array $path_fragments ) {
		$URL = $base_URL;
		$URL .= $this->relative_URI;


		if($path_fragments) {
			foreach($path_fragments as $i=>$p) {
				$path_fragments[$i] = rawurlencode( $p );
			}

			$path_fragments = implode('/', $path_fragments).'/';

			$URL .= $path_fragments;
		}

		if($GET_params) {
			foreach( $GET_params as $k=>$v ) {
				if(is_object($v)) {
					$GET_params[$k] = (string)$v;
				}
			}

			$query = http_build_query( $GET_params );

			$URL .= '?'.$query;
		}

		return $URL;

	}


	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getURL(array $GET_params= [], array $path_fragments= []) {

		if(
			(string)$this->site_id == Mvc::getCurrentSite()->getSiteId() &&
			(string)$this->locale == Mvc::getCurrentLocale() &&
			$this->getSSLRequired() == Mvc::getIsSSLRequest()
		) {

			return $this->getURI( $GET_params, $path_fragments );
		} else {

			return $this->getFullURL( $GET_params, $path_fragments );
		}
	}

	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getURI(array $GET_params= [], array $path_fragments= []) {
		$site = $this->getSite();

		if($this->getSSLRequired()) {
			$base_URL = $site->getDefaultSslURL( $this->locale )->getPathPart();
		} else {
			$base_URL = $site->getDefaultURL( $this->locale )->getPathPart();
		}


		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getFullURL(array $GET_params= [], array $path_fragments= []) {
		$site = $this->getSite();

		if($this->getSSLRequired()) {
			$base_URL = $site->getDefaultSslURL( $this->locale );
		} else {
			$base_URL = $site->getDefaultURL( $this->locale );
		}


		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}


	/**
	 * Example: //domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSchemaURL(array $GET_params= [], array $path_fragments= []) {

		$site = $this->getSite();

		$base_URL = $site->getDefaultURL( $this->locale );

		$schema = $base_URL->getSchemePart();

		$base_URL = substr($base_URL, strlen($schema));

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * Example: http://domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getNonSslURL(array $GET_params= [], array $path_fragments= []) {
		$site = $this->getSite();

		$base_URL = $site->getDefaultURL( $this->locale );

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}

	/**
	 * Example: https://domain/page/
	 *
	 * @param array $GET_params
	 * @param array $path_fragments
	 *
	 * @return string
	 */
	public function getSslURL(array $GET_params= [], array $path_fragments= []) {
		$site = $this->getSite();

		$base_URL = $site->getDefaultSslURL( $this->locale );

		return $this->_createURL($base_URL, $GET_params, $path_fragments);
	}


	/**
	 * @return string
	 */
	public function getLayoutsPath() {
		if($this->getCustomLayoutsPath()) {
			return $this->getCustomLayoutsPath();
		}

		return $this->getSite()->getLayoutsPath();
	}


	/**
	 * @param string $layouts_dir
	 */
	public function setCustomLayoutsPath($layouts_dir)
	{
		$this->custom_layouts_path = $layouts_dir;
	}

	/**
	 * @return string
	 */
	public function getCustomLayoutsPath()
	{
		return $this->custom_layouts_path;
	}


	/**
	 * @return string
	 */
	public function getLayoutScriptName() {
		return $this->layout_script_name;
	}

	/**
	 * @param string $layout_script_name
	 */
	public function setLayoutScriptName($layout_script_name) {
		$this->layout_script_name = $layout_script_name;
	}

	/**
	 * @throws Exception
	 *
	 */
	public function initializeLayout() {
		if(Mvc_Layout::getCurrentLayout()) {
			return;
		}

		Mvc_Layout::initCurrentLayout(
			$this->getLayoutsPath(),
			$this->getLayoutScriptName()
		);
	}

	/**
	 * @param bool $get_default
	 *
	 * @return string
	 */
	public function getHeadersSuffix( $get_default=false ) {

		if( $get_default && !$this->headers_suffix) {
			return $this->getSiteLocalizedData()->getDefaultHeadersSuffix();
		}

		return $this->headers_suffix;
	}

	/**
	 * @param string $headers_suffix
	 */
	public function setHeadersSuffix( $headers_suffix ) {
		$this->headers_suffix = $headers_suffix;
	}

	/**
	 * @param bool $get_default
	 *
	 * @return string
	 */
	public function getBodyPrefix( $get_default=false ) {
		if( $get_default && !$this->body_prefix) {
			return $this->getSiteLocalizedData()->getDefaultBodyPrefix();
		}

		return $this->body_prefix;
	}

	/**
	 * @param string $body_prefix
	 */
	public function setBodyPrefix( $body_prefix ) {
		$this->body_prefix = $body_prefix;
	}

	/**
	 * @param bool $get_default
	 * @return string
	 */
	public function getBodySuffix( $get_default=false ) {

		if( $get_default && !$this->body_suffix ) {
			return $this->getSiteLocalizedData()->getDefaultBodySuffix();
		}

		return $this->body_suffix;
	}

	/**
	 * @param string $body_suffix
	 */
	public function setBodySuffix( $body_suffix ) {
		$this->body_suffix = $body_suffix;
	}

	/**
	 * @return bool
	 */
	public function getSSLRequired() {
		return $this->SSL_required;
	}

	/**
	 * @param bool $SSL_required
	 */
	public function setSSLRequired($SSL_required) {
		$this->SSL_required = (bool)$SSL_required;
	}

	/**
	 * @return array
	 */
	public function getHttpHeaders()
	{
		return $this->http_headers;
	}

	/**
	 * @param array $http_headers
	 */
	public function setHttpHeaders( array $http_headers )
	{
		$this->http_headers = $http_headers;
	}

	/**
	 *
	 */
	public function handleHttpHeaders()
	{
		foreach( $this->http_headers as $header=>$value ) {
			if( is_int($header) ){
				Http_Headers::sendHeader($value);
			} else {
				if(is_array($value)){
					$value = implode('; ', $value);
				}

				Http_Headers::sendHeader( $header.': '.$value);
			}

		}

	}

	/**
	 * @param bool $get_default (optional)
	 *
	 * @return Mvc_Page_MetaTag_Interface[]
	 */
	public function getMetaTags( $get_default=false ) {
		if($get_default) {
			$meta_tags = [];

			foreach($this->getSiteLocalizedData()->getDefaultMetaTags() as $mt) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if($key==':') {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			foreach($this->meta_tags as $mt) {
				$key = $mt->getAttribute().':'.$mt->getAttributeValue();
				if($key==':') {
					$key = $mt->getContent();
				}
				$meta_tags[$key] = $mt;
			}

			return $meta_tags;

		}

		return $this->meta_tags;
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface $meta_tag
	 */
	public function addMetaTag( Mvc_Page_MetaTag_Interface $meta_tag) {
		$this->meta_tags[] = $meta_tag;
	}

	/**
	 * @param int $index
	 */
	public function removeMetaTag( $index ) {
		unset( $this->meta_tags[$index] );
	}

	/**
	 * @param Mvc_Page_MetaTag_Interface[] $meta_tags
	 */
	public function  setMetaTags( $meta_tags ) {
		$this->meta_tags = [];

		foreach( $meta_tags as $meta_tag ) {
			$this->addMetaTag( $meta_tag );
		}
	}


	/**
	 * @return string|null
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 *
	 * @return Mvc_Page_Content_Interface[]
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param Mvc_Page_Content_Interface $content
	 */
	public function addContent( Mvc_Page_Content_Interface $content) {
		$content->setContentId( count($this->content) );
		$content->setPage( $this );

		$this->content[] = $content;
	}

	/**
	 * @param int $index
	 */
	public function removeContent( $index ) {
		unset( $this->content[$index] );
	}

	/**
	 * @param Mvc_Page_Content_Interface[] $content
	 */
	public function setContent( $content ) {
		$this->content = [];

		foreach($content as $c ) {
			$this->addContent( $c );
		}
	}

	/**
	 * @param string $site_id
	 * @param Locale|string $locale (optional)
	 *
	 * @return Mvc_Page_Interface[]
	 */
	public function getList($site_id, $locale ) {

		$site = Mvc_Site::get($site_id);

		if(is_string($locale)) {
			$locale = new Locale($locale);
		}

		$homepage = $site->getHomepage( $locale );

		$result = [];

		$this->_getList($homepage, $result);

		return $result;
	}

	/**
	 * @param Mvc_Page_Interface $parent
	 * @param array $result
	 */
	protected function _getList( Mvc_Page_Interface $parent, array &$result ) {
		$result[] = $parent;
		foreach( $parent->getChildren() as $page ) {
			$this->_getList($page, $result);
		}
	}


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $data_file_path
	 *
	 * @param $URL_fragment
	 * @param array $parent_page_data
	 *
	 * @throws Mvc_Page_Exception
	 * @return array
	 */
	public static function _loadPages_readPageDataFile( Mvc_Site_Interface $site, Locale $locale, $data_file_path, $URL_fragment, array $parent_page_data=null ) {

		if(!IO_File::isReadable($data_file_path)) {
			throw new Mvc_Page_Exception( 'Page data file is not readable: '.$data_file_path, Mvc_Page_Exception::CODE_UNABLE_TO_READ_PAGE_DATA );
		}

		/** @noinspection PhpIncludeInspection */
		$current_page_data = require $data_file_path;

		$current_page_data['URL_fragment'] = rawurlencode($URL_fragment);
		$current_page_data['data_file_path'] = $data_file_path;
		$current_page_data['site_id'] = $site->getSiteId();
		$current_page_data['locale'] = $locale;

		if($parent_page_data) {
			$current_page_data['relative_URI'] = $parent_page_data['relative_URI'].$current_page_data['URL_fragment'].'/';

			$parent_page_id = $parent_page_data['id'];

			if(isset($parent_page_data['contents'])) {
				unset($parent_page_data['contents']);
			}
			unset($parent_page_data['id']);

			foreach( $parent_page_data as $k=>$v ) {
				if( in_array($k, static::$do_not_inherit_properties) ) {
					continue;
				}

				if(!array_key_exists($k, $current_page_data)) {
					$current_page_data[$k] = $v;
				}
			}

			$current_page_data['parent_id'] = $parent_page_id;

		} else {

			$current_page_data['relative_URI'] = '/';
			$current_page_data['parent_id'] = '';
			$current_page_data['id'] = Mvc_Page::HOMEPAGE_ID;
		}


		return $current_page_data;
	}


	/**
	 * @param array $data
	 * @param Mvc_Page $parent_page
	 *
	 * @throws Mvc_Page_Exception
	 *
	 * @return Mvc_Page
	 */
	public static function _loadPages_createPage( array $data, Mvc_Page $parent_page=null ) {

		//This must be really fast

		/**
		 * @var Mvc_Page $page
		 */
		$page = new static();

		$page->setSiteId( $data['site_id'] );
		$page->setLocale( $data['locale'] );
		$page->setPageId( $data['id'] );
		unset( $data['id'] );

		$page->parent_id = $data['parent_id'];
		$page->data_file_path = $data['data_file_path'];

		if(!isset($data['breadcrumb_title'])) {
			$data['breadcrumb_title'] = $data['title'];
		}
		if(!isset($data['menu_title'])) {
			$data['menu_title'] = $data['title'];
		}


		$meta_tags = [];

		foreach( $data['meta_tags']  as $i=>$m_dat) {
			$m_dat['site_id'] = $page->getSiteId();
			$m_dat['locale'] = $page->getLocale();
			$m_dat['page_id'] = $page->getPageId();
			$m_dat['meta_tag_id'] = $i;

			$mtg = Mvc_Factory::getPageMetaTagInstance();

			$mtg->setData( $m_dat );

			$meta_tags[] = $mtg;

		}
		unset($data['meta_tags']);
		$page->setMetaTags($meta_tags);



		$contents = [];
		//$content_form = Mvc_Factory::getPageContentInstance()->getCommonForm();

		foreach( $data['contents']  as $i=>$c_dat) {


			$m_dat['site_id'] = $page->getSiteId();
			$m_dat['locale'] = $page->getLocale();
			$m_dat['page_id'] = $page->getPageId();
			$c_dat['content_id'] = $i;

			$cnt = Mvc_Factory::getPageContentInstance();
			$cnt->setData($c_dat);

			$contents[] = $cnt;
		}
		unset($data['contents']);
		$page->setContent($contents);

		foreach( $data as $key=>$var ) {
			$page->{$key} = $var;
		}


		if($parent_page) {
			$page->_parent = $parent_page;
			$parent_page->_children[] = $page;
		}

		$page_key = $page->getPageKey();

		if(isset(static::$loaded_pages[$page_key])) {
			throw new Mvc_Page_Exception( 'Duplicates page key: \''.$page_key.'\' ', Mvc_Page_Exception::CODE_DUPLICATES_PAGE_ID  );
		}

		static::$loaded_pages[$page_key] = $page;

		$site_id = $page->getSiteId();
		$locale = $page->getLocale()->toString();

		if(!isset(static::$relative_URIs_map[$site_id])) {
			static::$relative_URIs_map[$site_id] = [];
		}
		if(!isset(static::$relative_URIs_map[$site_id][$locale])) {
			static::$relative_URIs_map[$site_id][$locale] = [];
		}

		static::$relative_URIs_map[$site_id][$locale][rawurldecode($page->relative_URI)] = $page_key;


		return $page;
	}


	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $root_dir
	 * @param array $parent_page_data (optional)
	 * @param Mvc_Page $parent_page
	 *
	 */
	protected static function _loadPages_readDir( Mvc_Site_Interface $site, Locale $locale, $root_dir, array $parent_page_data=null, Mvc_Page $parent_page=null ) {
		$list = IO_Dir::getList( $root_dir, '*', true, false );

		if(!$parent_page_data) {
			$page_data_file_path = $root_dir.self::PAGE_DATA_FILE_NAME;
			$URL_fragment = '';

			$page_data = static::_loadPages_readPageDataFile( $site, $locale, $page_data_file_path, $URL_fragment );
			$page = static::_loadPages_createPage( $page_data );


			$parent_page_data = $page_data;
			$parent_page = $page;

		}


		$data_file_name = self::PAGE_DATA_FILE_NAME;

		foreach( $list as $path=>$file ) {

			$URL_fragment = $file;

			$page_data = static::_loadPages_readPageDataFile( $site, $locale, $path.$data_file_name, $URL_fragment, $parent_page_data );
			$page = static::_loadPages_createPage($page_data, $parent_page);

			static::_loadPages_readDir($site, $locale, $path, $page_data, $page);
		}

	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 *
	 */
	public static function loadPages( Mvc_Site_Interface $site, Locale $locale ) {

		$key = $site->getSiteId().':'.$locale;

		if(isset(static::$site_pages_loaded_flag[$key])) {
			return;
		}

		static::_loadPages_readDir( $site, $locale,  $site->getPagesDataPath( $locale ));

		static::$site_pages_loaded_flag[$key] = true;

		return;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 * @param $data
	 */
	protected function _getAllPagesTree( Mvc_Page_Interface $page, &$data ) {
		/**
		 * @var Mvc_Page $page
		 */
		$data[$page->getPageKey()] = [
			'id' => $page->getPageKey(),
			'parent_id' => $page->getParent()->getPageKey(),
			'name' => $page->getName()
		];

		foreach( $page->getChildren() as $page ) {
			$this->_getAllPagesTree($page, $data);
		}
	}

	/**
	 *
	 * @return Data_Tree_Forest
	 */
	public function getAllPagesTree() {

		$forest = new Data_Tree_Forest();
		$forest->setIdKey('id');
		$forest->setLabelKey('name');

		foreach( Mvc_Site::getList() as $site ) {
			foreach($site->getLocales() as $locale) {

				$homepage = $site->getHomepage( $locale );

				$tree = new Data_Tree();
				$tree->getRootNode()->setId( $homepage->getPageKey() );
				$tree->getRootNode()->setLabel( $homepage->getName() );

				$pages = [];
				foreach( $homepage->getChildren() as $page ) {
					$this->_getAllPagesTree($page, $pages);
				}

				$tree->setData($pages);

				$forest->appendTree($tree);


			}
		}

		return $forest;
	}

	/**
	 * @param Mvc_Site_Interface $site
	 * @param Locale $locale
	 * @param string $relative_URI
	 * @return Mvc_Page_Interface|null
	 */
	public function getByRelativeURI( Mvc_Site_Interface $site, Locale $locale, $relative_URI ) {
		static::loadPages($site, $locale);

		$str_locale = (string)$locale;

		if(!isset(static::$relative_URIs_map[$site->getSiteId()][$str_locale][$relative_URI])) {
			return null;
		}

		$id_s = static::$relative_URIs_map[$site->getSiteId()][$str_locale][$relative_URI];

		return static::$loaded_pages[$id_s];
	}


	/**
	 *
	 *
	 * @param string $site_id
	 * @param Locale $locale
	 * @param string $page_id
	 *
	 * @return Mvc_Page_Interface|null
	 */
	public static function _load($site_id, $locale, $page_id) {
		$site =  Mvc_Site::get( $site_id );

		static::loadPages($site, $locale);

		$key = $site_id.':'.$locale.':'.$page_id;

		if(!isset(static::$loaded_pages[$key])) {
			return null;
		}

		return static::$loaded_pages[$key];

	}


	/**
	 * @return array
	 */
	public function getChildrenIds() {
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getPageId();
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getChildrenKeys() {
		$result = [];

		foreach( $this->getChildren() as $page ) {
			$result[] = $page->getPageKey();
		}

		return $result;
	}

	/**
	 *
	 */
	public function sortChildren() {
		if($this->_children_sorted) {
			return;
		}

		uasort( $this->_children, function( Mvc_Page $a, Mvc_Page $b ) {
			$a_order = $a->getOrder();
			$b_order = $b->getOrder();

			if($a_order==$b_order) {
				return 0;
			}

			return ($a_order < $b_order) ? -1 : 1;
		} );

		$this->_children_sorted = true;
	}

	/**
	 * @return Mvc_Page_Interface[]
	 */
	public function getChildren() {
		$this->sortChildren();

		return $this->_children;
	}


	/**
	 * @return array
	 */
	public function getLayoutsList() {
		if(!$this->site_id) {
			return [];
		}

		$path = $this->getLayoutsPath();


		$_lj = IO_Dir::getFilesList( $path, '*.phtml');

		$layouts = [];

		foreach($_lj as $lj) {
			$lj = substr($lj, 0, -6);

			$layouts[$lj] = basename($lj);
		}

		return $layouts;
	}

	/**
	 *
	 */
	public function handleDirectOutput() {
		$file_path = dirname( $this->getDataFilePath() ).'/'.$this->getDirectOutputFileName();

		if(!IO_File::exists($file_path)) {
			throw new Mvc_Page_Exception('Direct output file '.$file_path.' does not exist');
		}

		/** @noinspection PhpIncludeInspection */
		require $file_path;
	}


	/**
	 * @return Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	public function getBreadcrumbNavigation() {

		if( !$this->breadcrumb_navigation ) {
			$this->breadcrumb_navigation = [];

			$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
			$navigation_data->setPage( $this );

			$this->breadcrumb_navigation[] = $navigation_data;

			$parent = $this;
			while( ($parent = $parent->getParent()) ) {

				$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
				$navigation_data->setPage( $parent );

				array_unshift($this->breadcrumb_navigation, $navigation_data);

			}

		}

		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			$bd->setIsLast( $i==$last );
		}

		return $this->breadcrumb_navigation;
	}

	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract $item
	 */
	public function addBreadcrumbNavigationItem( Mvc_NavigationData_Breadcrumb_Abstract $item  ) {

		$this->getBreadcrumbNavigation();

		$this->breadcrumb_navigation[] = $item;
		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			/**
			 * @var Mvc_NavigationData_Breadcrumb_Abstract $bd
			 */
			$bd->setIsLast( $i==$last );
		}

	}

	/**
	 * @param string $title
	 * @param string $URI (optional)
	 */
	public function addBreadcrumbNavigationData( $title, $URI='' ) {

		$bn = Mvc_Factory::getNavigationDataBreadcrumbInstance();

		$bn->setTitle( $title );
		$bn->setURI( $URI );

		$this->addBreadcrumbNavigationItem($bn);

	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function addBreadcrumbNavigationPage( Mvc_Page_Interface $page ) {

		$bn = Mvc_Factory::getNavigationDataBreadcrumbInstance();
		$bn->setPage( $page );

		$this->addBreadcrumbNavigationItem( $bn );
	}


	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract[] $data
	 * @throws Exception
	 */
	public function setBreadcrumbNavigation( $data ) {

		$this->breadcrumb_navigation = [];

		foreach( $data as $dat ) {
			$this->addBreadcrumbNavigationItem( $dat );
		}
	}

	/**
	 *
	 * @param int $shift_count
	 */
	public function breadcrumbNavigationShift( $shift_count ) {

		$this->getBreadcrumbNavigation();
		if($shift_count<0) {
			$shift_count = count($this->breadcrumb_navigation)+$shift_count;
		}

		for($c=0;$c<$shift_count;$c++) {
			array_shift($this->breadcrumb_navigation);
		}
	}



	/**
	 *
	 * @return bool
	 */
	public function parseRequestURL() {
		$router = Mvc::getCurrentRouter();

		$path = implode('/', $router->getPathFragments());

		if(strpos($path, '..')==false) {
			$path = $this->getDataDirPath().$path;

			if(IO_File::exists($path)) {
				$router->setIsFile( $path );

				return true;
			}

		}


		foreach($this->getContent() as $content ) {

			$module = Application_Modules::getModuleInstance($content->getModuleName());

			if(!$module) {
				continue;
			}

			$controller = $module->getControllerInstance( $content );

			if($controller->parseRequestURL($content)) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @param string $file_path
	 */
	public function handleFile( $file_path ) {

		$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

		if(in_array($ext, static::$php_file_extensions)) {

			/** @noinspection PhpIncludeInspection */
			require $file_path;
		} else {
			IO_File::send($file_path);
		}

		Application::end();

	}


	/**
	 * @param string $auth_controller_module_name
	 */
	public function setAuthControllerModuleName($auth_controller_module_name)
	{
		$this->auth_controller_module_name = $auth_controller_module_name;
	}

	/**
	 *
	 * @return string
	 */
	public function getAuthControllerModuleName()
	{
		return $this->auth_controller_module_name;
	}


	/**
	 * @return string
	 */
	public function render() {
		if(
			($output=$this->getOutput())
		) {
			return $output;
		}

		$this->initializeLayout();

		Debug_Profiler::MainBlockStart('Content dispatch');


		$translator_namespace = Translator::COMMON_NAMESPACE;

		Translator::setCurrentNamespace( $translator_namespace );

		foreach($this->getContent() as $content ) {
			Mvc::setCurrentContent($content);

			$content->dispatch();

			Mvc::unsetCurrentContent();
		}

		Translator::setCurrentNamespace( $translator_namespace );

		$output = Mvc_Layout::getCurrentLayout()->render();

		Debug_Profiler::MainBlockEnd('Content dispatch');

		return $output;

	}

}