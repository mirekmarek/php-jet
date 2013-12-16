<?php
/**
 *
 *
 *
 * Default router class
 *
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Default extends Mvc_Router_Abstract {


	/**
	 * Request URL
	 *
	 * @var string
	 */
	protected $URL = "";

	/**
	 * @var Http_URL
	 */
	protected $parsed_URL;

	/**
	 *  Example:
	 *
	 * http://host/path
	 *
	 * base URL is = http://host
	 *
	 * http://host:8443/path
	 *
	 * base URL is = http://host:8443
	 *
	 *
	 * @var string
	 */
	protected $base_URL = "";

	/**
	 * Request path fragments (http://host/path-fragment-0/path-fragment-1/ )
	 *
	 * @var string[]
	 */
	protected $path_fragments = array();

	/**
	 * Path fragments that have been used by modules.
	 * It must resolve if the URL is valid or not. In other words it must prevent duplicities.
	 *
	 * So each module which is using path fragment must let route known about it.
	 *
	 *
	 * @var string[]
	 */
	protected $used_path_fragments = array();

	/**
	 * Is it SSL (https) request?
	 * @var bool
	 */
	protected $is_SSL_request = false;

	//------------------------------------------------------------------

	/**
	 *
	 * @var Mvc_Sites_Site_ID_Abstract
	 */
	protected $site_ID;

	/**
	 * Current Site data
	 *
	 * @see Mvc_Sites
	 * @see Mvc/readme.txt
	 *
	 * @var Mvc_Sites_Site_Abstract
	 */
	protected $site;

	/**
	 *
	 * @var Mvc_Pages_Page_ID_Abstract
	 */
	protected $page_ID;

	/**
	 * Current page data
	 * Is null if:
	 *	- It is non-standard service type (AJAX, SYS, REST and so on)
	 *
	 * @see Mvc_Pages
	 * @see Mvc/readme.txt
	 *
	 * @var Mvc_Pages_Page_Abstract
	 */
	protected $page;

	/**
	 *
	 * @var bool
	 */
	protected $is_admin_UI = false;

	/**
	 * @var bool
	 */
	protected $page_is_publicly_accessible = false;

	/**
	 *
	 * @var Mvc_Layout
	 */
	protected $layout;

	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected $public_file_path = "";

	//------------------------------------------------------------------

	/**
	 * Service type.
	 * Options: Standard, AJAX, REST, SYS, .... @see Mvc_Router_Abstract::$path_fragments_service_types_map
	 *
	 * Also defined a constant JET_SERVICE_TYPE
	 *
	 * @see Mvc/readme.txt
	 *
	 * @var string
	 */
	protected $service_type = "";

	/**
	 * Reserved ...
	 *
	 * @var string
	 */
	protected $service_subtype = "";

	//------------------------------------------------------------------


	/**
	 * Current module name
	 * Relates: AJAX, SYS, REST, ....
	 *
	 * Is empty if:
	 *	- it is standard request
	 *
	 * @see Mvc/readme.txt
	 * @see Mvc_Modules
	 *
	 * @var string
	 */
	protected $module_name = "";

	/**
	 * Current module action (accurately controller action)
	 * Relates: AJAX, SYS, REST, ....
	 *
	 * @see Mvc/readme.txt
	 * @see Mvc_Modules
	 *
	 * @var string
	 */
	protected $module_action = Mvc_Dispatcher::DEFAULT_ACTION;

	//------------------------------------------------------------------
	/**
	 * @var bool
	 */
	protected $is_404 = false;

	//------------------------------------------------------------------

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var bool
	 */
	protected $is_redirect = false;

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var string
	 */
	protected $redirect_target_URL = "";

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 * Options: Mvc_Router::REDIRECT_TYPE_TEMPORARY, Mvc_Router::REDIRECT_TYPE_PERMANENTLY
	 *
	 * @var string
	 */
	protected $redirect_type = "";


	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected $_cache_loaded = false;

	/**
	 * @var Mvc_Layout_OutputPart[]
	 */
	protected $cache_output_parts = array();

	/**
	 * @var string|null
	 */
	protected $cache_output = null;

	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected $_authentication_required = true;

	/**
	 *
	 * @var Mvc_UIManagerModule_Abstract
	 */
	protected $_UI_manager_module_instance = null;

	/**
	 *
	 * @var Auth_ManagerModule_Abstract
	 */
	protected $_auth_module_instance = null;

	/**
	 * @var Mvc_Dispatcher_Abstract
	 */
	protected $_dispatcher_instance = null;

	/**
	 * Initializes the router.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @param string $URL
	 * @param bool|null $cache_enabled (optional, default: by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 * @return mixed
	 */
	public function initialize( $URL, $cache_enabled=null ) {

		if( !$URL ) {
			throw new Mvc_Router_Exception(
				"URL is not defined",
				Mvc_Router_Exception::CODE_URL_NOT_DEFINED
			);
		}

		if($cache_enabled!==null) {
			$this->cache_enabled = (bool)$cache_enabled;
		} else {
			$this->cache_enabled = $this->_config->getCacheEnabled();
		}
        $this->output_cache_enabled = $this->cache_enabled;

		$this->URL = $URL;

		if($this->cacheRead()) {
			return true;
		}

		$this->parsed_URL = Http_URL::parseURL($URL);

		if( !$this->parsed_URL->getIsValid() ) {
			throw new Mvc_Router_Exception(
				"Unable to parse URL",
				Mvc_Router_Exception::CODE_UNABLE_TO_PARSE_URL
			);
		}


		$this->base_URL = $this->parsed_URL->getScheme()."://".$this->parsed_URL->getHost();
		if($this->parsed_URL->getPort()) {
			$this->base_URL .= ":".$this->parsed_URL->getPort();
		}

		$this->path_fragments = explode( "/", $this->parsed_URL->getPath() );

		if(!$this->validateURIFormat()) {
			return true;
		}

		return $this->resolve();
	}


	/**
	 * Resolve:
	 *  - site
	 *  - page
	 *  - service type
	 *  (- module and action)
	 * @see Mvc/readme.txt
	 *
	 * @return bool
	 */
	protected function resolve() {

		$path = $this->path_fragments;

		$page_i = Mvc_Factory::getPageInstance();

		//---------------------------------------
		$URLs = array();
		$URIs = array();
		for($i=count($this->path_fragments); $i>=0; $i--) {
			if($i>0) {
				$URI = "/".implode("/", $path)."/";
				unset($path[count($path)-1]);
			} else {
				$URI = "/";
			}

			$URIs[] = $URI;
			$URLs[] = $this->base_URL.$URI;
		}


		$page = $page_i->getByURL($URLs);

		if(!$page) {
			//Unknown? Let's redirect to the default ...

			$default_site = Mvc_Factory::getSiteInstance()->getDefault();

			if(!$default_site) {
				return false;
			}

			$default_URL = $default_site->getDefaultURL($default_site->getDefaultLocale());
			if(!$default_URL) {
				return false;
			}

			$this->setIsRedirect($default_URL, Mvc_Router::REDIRECT_TYPE_TEMPORARY);

			return true;
		}

		$URL_index = array_search($page->getURI(), $URIs);

		$i = count($this->path_fragments) - $URL_index;
		$URL = $URLs[$URL_index];

		for($c=0; $c<$i; $c++) {
			array_shift( $this->path_fragments );
		}

		$redirect_default_URL = false;
		if($page->getSSLRequired()) {
			if($URL!=$page->getSslURL()) {
				$redirect_default_URL = $page->getSslURL();
			}
		} else {
			if(
				$URL!=$page->getNonSslURL() &&
				$URL!=$page->getSslURL()
			) {
				$redirect_default_URL = $page->getNonSslURL();
			}
		}


		if($redirect_default_URL) {
			//OK, we have page. But given URL is not default URL. So redirect to default ...
			$this->setIsRedirect(
				$redirect_default_URL
					. implode("/", $this->path_fragments)
					. ( $this->path_fragments ? "/" : "" )
					. $this->parsed_URL->getQuery()
			);

			return true;
		}


		$this->page_ID = $page->getID();
		$this->page = $page;

		$this->site_ID = $page->getSiteID();
		$this->site = Mvc_Sites::getSite($this->site_ID);

		$this->is_admin_UI = $this->page->getIsAdminUI();

		if(
			$this->page->getIsAdminUI() ||
			$this->page->getAuthenticationRequired()
		) {
			$this->page_is_publicly_accessible = false;
            $this->output_cache_enabled = false;
		} else {
			$this->page_is_publicly_accessible = true;
		}


		//service (Standard, AJAX, REST)
		$this->service_type = Mvc_Router::SERVICE_TYPE_STANDARD;

		if(
			isset($this->path_fragments[0]) &&
			isset( static::$path_fragments_service_types_map[$this->path_fragments[0]] )
		) {
			$this->service_type = static::$path_fragments_service_types_map[$this->path_fragments[0]];
			array_shift($this->path_fragments);
		}

		if($this->service_type!=Mvc_Router::SERVICE_TYPE_STANDARD) {
            $this->output_cache_enabled = false;
		}

		if(
			$this->service_type!=Mvc_Router::SERVICE_TYPE_STANDARD &&
			$this->service_type!=Mvc_Router::SERVICE_TYPE__JETJS_ &&
			$this->path_fragments
		) {
			$this->module_name = str_replace(".","\\", array_shift( $this->path_fragments ));

			if($this->path_fragments){
				$this->module_action = array_shift( $this->path_fragments );
			}

			if( $this->service_type==Mvc_Router::SERVICE_TYPE_REST ) {
				$method = strtolower(Http_Request::getRequestMethod());

				$this->module_action = $method . "_" . $this->module_action;
			}

		}

		if(
			$this->service_type == Mvc_Router::SERVICE_TYPE_STANDARD &&
			count($this->path_fragments)==1 &&
			strpos($this->path_fragments[0], ".")!==false &&
			$this->path_fragments[0][0] != "." &&
			strpos($this->path_fragments[0], "..")===false
		) {
			$file_path = $this->getPublicFilesPath().$this->path_fragments[0];

			if(IO_File::isReadable($file_path)) {
				$this->setIsPublicFile($file_path);
			}
		}

		return true;
	}

	/**
	 * @param string $public_file_path
	 */
	public function setIsPublicFile( $public_file_path ) {
		$this->public_file_path = $public_file_path;
		$this->cacheSave();
	}

	/**
	 * @abstract
	 *
	 * @return bool
	 */
	public function getIsPublicFile() {
		return (bool)$this->public_file_path;
	}


	/**
	 * Try to download site public files (example: sitemap.xml, robot.txt, favicon.ico, ....)
	 *
	 */
	public function handlePublicFile() {
		if( !$this->public_file_path ) {
			return;
		}

		$this->cacheSave();

		IO_File::download(
			$this->public_file_path
		);
	}

	/**
	 * Sets the request is unknown page
	 *
	 * @return mixed
	 */
	public function setIs404() {
		$this->is_404 = true;
		$this->cacheSave();
	}

	/**
	 * Returns true is request is unknown page
	 *
	 * @return bool
	 */
	public function getIs404() {
		return $this->is_404;
	}

	/**
	 *
	 */
	public function handle404() {
		if(!$this->is_404) {
			return;
		}

		$this->cacheSave();
		$this->getUIManagerModuleInstance()->handle404();
	}




	/**
	 * Sets the redirect. Redirection is not performed immediately, but after operations such as storage of records to cache and so on.
	 *
	 * @param string $target_URL
	 * @param string $type (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	public function setIsRedirect( $target_URL, $type=NULL ) {
		if(!$type ) {
			$type = Mvc_Router::REDIRECT_TYPE_TEMPORARY;
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = $type;
		$this->cacheSave();
	}


	/**
	 * @return bool
	 */
	public function getIsRedirect() {
		return $this->is_redirect;
	}

	/**
	 * @return string
	 */
	public function getRedirectTargetURL() {
		return $this->redirect_target_URL;
	}

	/**
	 * @return string
	 */
	public function getRedirectType() {
		return $this->redirect_type;
	}

	/**
	 * Redirect if needed
	 */
	public function handleRedirect() {
		if( !$this->is_redirect ) {
			return;
		}

		$this->cacheSave();

		if($this->redirect_type==Mvc_Router::REDIRECT_TYPE_PERMANENTLY) {
			Http_Headers::movedPermanently($this->redirect_target_URL);
		} else {
			Http_Headers::movedTemporary($this->redirect_target_URL);
		}
	}



	/**
	 * Validated the URI path format. Returns true if the format is OK and the redirect is not needed.
	 *
	 * - last char in URI path must be / ( ... or some document. example: .html )
	 *
	 * @return bool
	 */
	protected function validateURIFormat() {

		array_shift( $this->path_fragments );
		$end_i = count($this->path_fragments)-1;

		//last char in URI path must be /
		if( $this->path_fragments[$end_i]==="" ) {
			unset($this->path_fragments[$end_i]);

			return true;
		}

		//... or some opened document, or XML and so on
		if( strpos( $this->path_fragments[$end_i], ".")!==false ) {
			return true;
		}

		return true;

		/*
		$this->setRedirect(
			$this->base_URL
				. $this->path . "/"
				. (($this->query) ? "?".$this->query : ""),
			Mvc_Router::REDIRECT_TYPE_PERMANENTLY
		);

		return false;
		*/
	}


	/**
	 * @return boolean
	 */
	public function getPageIsPubliclyAccessible() {
		return $this->page_is_publicly_accessible;
	}

	/**
	 * Returns name of main site UI module
	 *
	 * @return string
	 */
	public function getUIManagerModuleName() {
		$force_UI_manager_module_name = $this->page->getForceUIManagerModuleName();

		if( $force_UI_manager_module_name ) {
			return $force_UI_manager_module_name;
		} else {
			if($this->is_admin_UI) {
				return $this->_config->getDefaultAdminUIManagerModuleName();
			} else {
				return $this->_config->getDefaultSiteUIManagerModuleName();
			}

		}
	}

	/**
	 * Returns instance of main site UI module
	 *
	 * @throws Mvc_Router_Exception
	 *
	 * @return Mvc_UIManagerModule_Abstract
	 */
	public function getUIManagerModuleInstance() {
		if($this->_UI_manager_module_instance) {
			return $this->_UI_manager_module_instance;
		}

		$module_name = $this->getUIManagerModuleName();

		$module_instance = Application_Modules::getModuleInstance( $module_name );

		if(!$module_instance instanceof Mvc_UIManagerModule_Abstract) {
			throw new Mvc_Router_Exception(
				"Invalid Site UI module class. Main '{$module_name}' module class must be subclass of Mvc_UIManagerModule_Abstract",
				Mvc_Router_Exception::CODE_INVALID_SITE_UI_CLASS
			);

		}

		$this->_UI_manager_module_instance = $module_instance;
		$this->_UI_manager_module_instance->setupRouter( $this );

		return $this->_UI_manager_module_instance;
	}


	/**
	 * @return string
	 */
	public function getAuthManagerModuleName() {
		return $this->_config->getDefaultAuthManagerModuleName();
	}

	/**
	 *
	 * @return Auth_ManagerModule_Abstract
	 */
	public function getAuthManagerModuleInstance() {
		return $this->_auth_module_instance;
	}

	/**
	 * Setup error handler if any changes from default settings
	 *
	 */
	public function setupErrorHandler() {
		Debug_ErrorHandler::setHTTPErrorPagesDir( $this->site->getBasePath() . "error_pages/" );
	}

	/**
	 * @param Auth_ManagerModule_Abstract $auth_manager_module_instance
	 */
	public function setAuthManagerModuleInstance( Auth_ManagerModule_Abstract $auth_manager_module_instance ) {
		$this->_auth_module_instance = $auth_manager_module_instance;
		$this->_auth_module_instance->setupRouter($this);

		if(!$this->page_is_publicly_accessible) {
			$this->_authentication_required = $this->_auth_module_instance->getAuthenticationRequired();

			if( $this->_authentication_required ) {
				$this->_UI_manager_module_instance = $this->_auth_module_instance;
			}
		} else {
			$this->_authentication_required = false;
		}
	}

	/**
	 *
	 */
	public function setupLayout() {
		if($this->layout) {
			return;
		}

		if(
			$this->service_type!=Mvc_Router::SERVICE_TYPE_REST &&
			$this->service_type!=Mvc_Router::SERVICE_TYPE_SYS
		) {
			$this->layout = $this->_UI_manager_module_instance->initializeLayout();
		}
	}



	/**
	 * @param Mvc_Dispatcher_Abstract $dispatcher_instance
	 */
	public function setDispatcherInstance( Mvc_Dispatcher_Abstract $dispatcher_instance) {
		$this->_dispatcher_instance = $dispatcher_instance;
	}

	/**
	 * @return Mvc_Dispatcher_Abstract
	 */
	public function getDispatcherInstance() {
		return $this->_dispatcher_instance;
	}

	/**
	 * @return string
	 */
	public function getURL() {
		return $this->URL;
	}

	/**
	 * @return Http_URL
	 */
	public function getParsedURL() {
		return $this->parsed_URL;
	}

	/**

	/**
	 * @return array
	 */
	public function getPathFragments() {
		return $this->path_fragments;
	}

	/**
	 * It must resolve if the URL is valid or not. In other words it must prevent duplicities.
	 *
	 * So each module which is using path fragment must let route known about it.
	 *
	 * @param string $used_path_fragment
	 */
	public function putUsedPathFragment( $used_path_fragment ) {
		if(!in_array($used_path_fragment, $this->used_path_fragments)) {
			$this->used_path_fragments[] = $used_path_fragment;
		}
	}

	/**
	 * @return bool
	 */
	public function getIsThereAnyUnusedPathFragment() {

		if( $this->service_type!=Mvc_Router::SERVICE_TYPE_STANDARD ) {
			return false;
		}

		if( count($this->path_fragments) != count($this->used_path_fragments) ) {
			return true;
		}

		$unused = array_diff( $this->path_fragments, $this->used_path_fragments );

		if( count( $unused ) ) {
			return true;
		}

		return false;
	}


	/**
	 * @return bool
	 */
	public function getIsSSLRequest() {
		return $this->is_SSL_request;
	}

	/**
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	public function getSiteID() {
		return $this->site_ID;
	}

	/**
	 *
	 * @return Mvc_Sites_Site_Abstract
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->getPage()->getLocale();
	}

	/**
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	public function getPageID() {
		return $this->page_ID;
	}

	/**
	 *
	 * @return Mvc_Pages_Page_Abstract
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @param Mvc_Pages_Page_Abstract $page
	 */
	public function setPage( Mvc_Pages_Page_Abstract $page ) {
		$this->page = $page;
		$this->page_ID = $page->getID();
	}

	/**
	 * @return string
	 */
	public function getServiceType() {
		return $this->service_type;
	}

	/**
	 * @return string|null
	 */
	public function getServiceSubtype() {
		return $this->service_subtype;
	}

	/**
	 * @return string
	 */
	public function getModuleName() {
		return $this->module_name;
	}

	/**
	 * @return string
	 */
	public function getModuleAction() {
		return $this->module_action;
	}

	/**
	 * @return string
	 */
	public function getBaseURI() {
		return JET_BASE_URI;
	}

	/**
	 * @return string
	 */
	public function getBaseURL() {
		return JET_BASE_URI;
	}

	/**
	 * @return string
	 */
	public function getModulesBaseURI() {
		return $this->getBaseURI()."modules/";
	}

	/**
	 * @return string
	 */
	public function getModulesBaseURL() {
		return $this->getBaseURL()."modules/";
	}

	/**
	 * @return string
	 */
	public function getPublicURI() {
		return $this->getBaseURI()."public/";
	}

	/**
	 * @return string
	 */
	public function getPublicURL() {
		return $this->getBaseURL()."public/";
	}

	/**
	 * @return string
	 */
	public function getPublicFilesPath() {
		return $this->site->getBasePath() . "public_files/";
	}

	/**
	 * @return string
	 */
	public function getSiteBaseURI() {
		return JET_SITES_URI . $this->site_ID . "/";
	}

	/**
	 * @return string
	 */
	public function getSiteImagesURI() {
		return JET_SITES_URI . $this->site_ID . "/images/";
	}

	/**
	 * @return string
	 */
	public function getSiteScriptsURI() {
		return JET_SITES_URI . $this->site_ID . "/scripts/";
	}

	/**
	 * @return string
	 */
	public function getSiteStylesURI() {
		return JET_SITES_URI . $this->site_ID . "/styles/";
	}

	/**
	 *
	 * @return Mvc_Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @param Mvc_Layout $layout
	 */
	public function setLayout( Mvc_Layout $layout) {
		$this->layout = $layout;
	}


	/**
	 * @param Mvc_Sites_Site_ID_Abstract $site_ID
	 */
	public function setSiteID(Mvc_Sites_Site_ID_Abstract $site_ID) {
		$this->site_ID = $site_ID;
		$this->site = Mvc_Sites::getSite($site_ID);
	}

	/**
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 */
	public function setPageID( Mvc_Pages_Page_ID_Abstract $page_ID ) {
		$this->page_ID = $page_ID;
		$this->page = Mvc_Pages::getPage($page_ID);
	}

	/**
	 * @param string $service_type
	 */
	public function setServiceType($service_type) {
		$this->service_type = $service_type;
	}

	/**
	 * @param string $service_subtype
	 */
	public function setServiceSubtype($service_subtype) {
		$this->service_subtype = $service_subtype;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName($module_name) {
		$this->module_name = $module_name;
	}

	/**
	 * @param string $module_action
	 */
	public function setModuleAction($module_action) {
		$this->module_action = $module_action;
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
	public function getAuthenticationRequired() {
		return $this->_authentication_required;
	}

	/**
	 * @return bool
	 */
	protected function cacheRead() {
		if(!$this->cache_enabled) {
			return false;
		}

		$cached_router = $this->getCacheBackendInstance()->load( $this->URL );

		if(!$cached_router) {
			return false;
		}

		foreach( get_object_vars($cached_router) as $property=>$value ) {
			if($property[0]=="_") {
				continue;
			}
			$this->{$property} = $value;
		}
		$this->_cache_loaded = true;

		if($this->layout) {
			$this->layout->setRouter($this);
		}

		return true;
	}

	/**
	 *
	 */
	public function cacheSave() {
		if(
			!$this->cache_enabled ||
			$this->_cache_loaded
		) {
			return;
		}

		$this->getCacheBackendInstance()->save($this->URL, $this);
	}

	/**
	 * Truncate cache. URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @param null|string|array $URL
	 */
	public function cacheTruncate( $URL=null ) {
		$this->getCacheBackendInstance()->truncate($URL);
	}

	/**
	 * @return bool
	 */
	public function getCacheLoaded() {
		return $this->_cache_loaded;
	}



	/**
	 * @param string $loop_ID
	 * @param Mvc_Layout_OutputPart $output_part
	 */
	public function setCacheOutputParts( $loop_ID, Mvc_Layout_OutputPart $output_part ) {
        if(!$this->output_cache_enabled) {
            return;
        }

		$_output_part = clone $output_part;
		if(!$output_part->getIsStatic()) {
			$_output_part->setOutput("");
		}

		$this->cache_output_parts[$loop_ID] = $_output_part;
	}

	/**
	 * @param string $loop_ID
	 *
	 * @return null|Mvc_Layout_OutputPart
	 */
	public function getCacheOutputParts( $loop_ID ) {
        if(!$this->output_cache_enabled) {
            return null;
        }
		return isset($this->cache_output_parts[$loop_ID]) ? $this->cache_output_parts[$loop_ID] : null;
	}


	/**
	 *
	 * @param $output
	 */
	public function setCacheOutput( $output ) {
        if(!$this->output_cache_enabled) {
            return;
        }

		$this->cache_output_parts = array();
		$this->cache_output = $output;
	}

	/**
	 * @return null|string
	 */
	public function getCacheOutput() {
        if(!$this->output_cache_enabled) {
            return null;
        }
		return $this->cache_output;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		$dat = array();
		foreach(get_object_vars($this) as $var=>$val) {
			if($var[0]=="_" ||
                ($var=="layout" && !$this->output_cache_enabled)
            ) {
				continue;
			}
			$dat[] = $var;
		}

		return $dat;
	}

	/**
	 * @return mixed
	 */
	public function helper_cache_getCreateCommand() {
		return $this->getCacheBackendInstance()->helper_getCreateCommand();
	}

	/**
	 *
	 */
	public function helper_cache_create() {
		$this->getCacheBackendInstance()->helper_create();
	}
}