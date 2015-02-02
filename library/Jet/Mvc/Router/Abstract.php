<?php
/**
 *
 *
 *
 * System router abstract class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

/**
 * Class Mvc_Router_Abstract
 *
 * @JetApplication_Signals:signal = '/router/initialized'
 * @JetApplication_Signals:signal = '/router/resolved'
 *
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getRouterInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Router_Abstract'
 */
abstract class Mvc_Router_Abstract extends Object {

	/**
	 *
	 * @var Mvc_Router_Config_Abstract
	 */
	protected $_config;

	/**
	 * @var bool
	 */
	protected $cache_enabled;

    /**
     * @var bool
     */
    protected $output_cache_enabled;

	/**
	 * @var bool
	 */
	protected $render_only = false;

	/**
	 * @param Mvc_Router_Config_Abstract $custom_config
	 */
	public function __construct( Mvc_Router_Config_Abstract $custom_config=null ) {
		if($custom_config) {
			$this->_config = $custom_config;
		} else {
			$this->_config = Mvc_Factory::getRouterConfigInstance();
		}
	}

	/**
	 * @param bool $render_only
	 */
	public function setRenderOnly($render_only) {
		$this->render_only = $render_only;
	}

	/**
	 * @return bool
	 */
	public function getRenderOnly() {
		return $this->render_only;
	}



	/**
	 * Enable router cache
	 */
	public function enableCache() {
		$this->cache_enabled = true;
	}

	/**
	 * Disable router cache
	 */
	public function disableCache() {
		$this->cache_enabled = false;
	}

    /**
     * Enable output cache
     */
    public function enableOutputCache() {
        $this->output_cache_enabled = true;
    }

    /**
     * Disable output cache
     */
    public function disableOutputCache() {
        $this->output_cache_enabled = false;
    }


	/**
	 * @return Mvc_Router_Map_Abstract
	 */
	abstract public function generateMap();

	/**
	 * @return Mvc_Router_Map_Abstract
	 */
	abstract public function getMap();


	/**
	 * Initializes the router.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @abstract
	 * @param string $request_URL
	 * @param bool|null $cache_enabled (optional, default: by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 * @return bool
	 */
	abstract public function initialize( $request_URL, $cache_enabled=null );

	/**
	 * Resolve:
	 *  - which site
	 *  - which page
	 *  - which service type
	 *  (- which module and action)
	 * @see Mvc/readme.txt
	 *
	 * @abstract
	 * @return bool
	 */
	abstract protected function resolve();


	/**
	 * @abstract
	 *
	 * @param string $public_file_path
	 */
	abstract public function setIsPublicFile( $public_file_path );

	/**
	 * @abstract
	 *
	 * @return bool
	 */
	abstract public function getIsPublicFile();


	/**
	 * Try to read and pass throw site public files (example: sitemap.xml, robot.txt, favicon.ico, ....)
	 *
	 * @abstract
	 */
	abstract public function handlePublicFile();


	/**
	 * Sets the request is unknown page
	 *
	 * @abstract
	 */
	abstract public function setIs404();


	/**
	 * Returns true is request is unknown page.
	 *
	 * @abstract
	 *
	 * @return bool
	 */
	abstract public function getIs404();

	/**
	 * @abstract
	 */
	abstract public function handle404();


	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsRedirect();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRedirectTargetURL();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRedirectType();

	/**
	 * Sets the redirect. Redirection is not performed immediately, but after operations such as storage of records to cache and so on.
	 *
	 * @abstract
	 * @param string $target_URL
	 * @param string $type (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	abstract public function setIsRedirect( $target_URL, $type=null );


	/**
	 * Redirect if needed
	 * @abstract
	 */
	abstract public function handleRedirect();


	/**
	 * Returns name of main site UI module
	 *
	 * @abstract
	 *
	 * @return string
	 */
	abstract public function getFrontControllerModuleName();

	/**
	 * Returns instance of main site UI module
	 *
	 * @abstract
	 *
	 * @return Mvc_FrontControllerModule_Abstract
	 */
	abstract public function getFrontController();

	/**
	 * @return string
	 */
	abstract public function getAuthControllerModuleName();

	/**
	 * @abstract
	 *
	 * @return Auth_ControllerModule_Abstract
	 */
	abstract public function getAuthController();

	/**
	 * Setup error handler if any changes from default settings
	 *
	 * @abstract
	 */
	abstract public function setupErrorHandler();

	/**
	 *
	 * @abstract
	 * @param Auth_ControllerModule_Abstract $auth_controller_module_instance
	 */
	abstract public function setAuthController( Auth_ControllerModule_Abstract $auth_controller_module_instance );

	/**
	 *
	 * @abstract
	 */
	abstract public function setupLayout();

	/**
	 * @return Mvc_Dispatcher_Queue
	 */
	abstract public function getDispatchQueue();


	/**
	 * @abstract
	 * @param Mvc_Dispatcher_Abstract $dispatcher
	 */
	abstract public function setDispatcherInstance( Mvc_Dispatcher_Abstract $dispatcher);

	/**
	 * @abstract
	 * @return Mvc_Dispatcher_Abstract
	 */
	abstract public function getDispatcherInstance();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getRequestURL();

	/**
	 * @return Http_URL
	 */
	abstract public function getParsedRequestURL();

	/**
	 * @abstract
	 * @return array
	 */
	abstract public function getPathFragments();

	/**
	 * @return array
	 */
	abstract public function shiftPathFragments();


	/**
	 * @param string $template  (example: 'page:%VAL%' )
	 * @param mixed $default_value
	 * @param int $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	abstract public function parsePathFragmentIntValue( $template, $default_value=null, $fragment_index=0 );

	/**
	 * @param string $template
	 * @param string $fragment_index
	 * @param string $reg_exp_part
	 *
	 * @return mixed
	 * @throws Exception
	 */
	abstract public function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part );

	/**
	 * @abstract
	 *
	 * It must resolve if the URL is valid or not. In other words it must prevent duplicities.
	 *
	 * So each module which is using path fragment must let route known about it.
	 *
	 * @param string $used_path_fragment
	 */
	abstract public function putUsedPathFragment( $used_path_fragment );

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsThereAnyUnusedPathFragment();

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsSSLRequest();

	/**
	 * @abstract
	 * @return Mvc_Sites_Site_ID_Abstract
	 */
	abstract public function getSiteID();

	/**
	 * @abstract
	 * @return Mvc_Sites_Site_Abstract
	 */
	abstract public function getSite();

	/**
	 * @abstract
	 * @return Locale
	 */
	abstract public function getLocale();

	/**
	 * @abstract
	 * @return Mvc_Pages_Page_ID_Abstract
	 */
	abstract public function getPageID();

	/**
	 * @abstract
	 * @return Mvc_Pages_Page_Abstract
	 */
	abstract public function getPage();

	/**
	 * @param Mvc_Pages_Page_Abstract $page
	 *
	 */
	abstract public function setPage( Mvc_Pages_Page_Abstract $page );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getServiceType();


	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getBaseURI();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getBaseURL();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getModulesBaseURI();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getModulesBaseURL();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getPublicURI();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getPublicURL();

	/**
	 * @return string
	 */
	abstract public function getPublicFilesPath();

	/**
	 * @return string
	 */
	abstract public function getSiteBaseURI();

	/**
	 * @return string
	 */
	abstract public function getSiteImagesURI();

	/**
	 * @return string
	 */
	abstract public function getSiteImagesPath();

	/**
	 * @return string
	 */
	abstract public function getSiteScriptsURI();
	/**
	 * @return string
	 */
	abstract public function getSiteScriptsPath();

	/**
	 * @return string
	 */
	abstract public function getSiteStylesURI();

	/**
	 * @return string
	 */
	abstract public function getSiteStylesPath();

	/**
	 * @abstract
	 * @return Mvc_Layout
	 */
	abstract public function getLayout();

	/**
	 * @abstract
	 * @param Mvc_Layout $layout
	 */
	abstract public function setLayout( Mvc_Layout $layout);

	/**
	 * @abstract
	 * @param Mvc_Sites_Site_ID_Abstract $site_ID
	 */
	abstract public function setSiteID(Mvc_Sites_Site_ID_Abstract $site_ID);
	/**
	 * @abstract
	 * @param Mvc_Pages_Page_ID_Abstract $page_ID
	 */
	abstract public function setPageID( Mvc_Pages_Page_ID_Abstract $page_ID );

	/**
	 * @abstract
	 * @param string $service_type
	 */
	abstract public function setServiceType($service_type);

	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getIsAdminUI();

	/**
	 * @abstract
	 * @param bool $is_admin_UI
	 */
	abstract public function setIsAdminUI($is_admin_UI);
	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function getAuthenticationRequired();

	/**
	 *
	 * @return Mvc_Router_Cache_Backend_Abstract
	 */
	protected function getCacheBackendInstance() {
		$backend_type = $this->_config->getCacheBackendType();
		return Mvc_Factory::getRouterCacheBackendInstance( $backend_type, Mvc_Factory::getRouterCacheBackendConfigInstance($backend_type) );
	}

	/**
	 *
	 * @return Mvc_Router_Map_Cache_Backend_Abstract
	 */
	protected function getMapCacheBackendInstance() {
		$backend_type = $this->_config->getMapCacheBackendType();
		return Mvc_Factory::getRouterMapCacheBackendInstance( $backend_type, Mvc_Factory::getRouterMapCacheBackendConfigInstance($backend_type) );
	}

	/**
	 * @abstract
	 *
	 * @param string $URL
	 *
	 * @return bool
	 */
	abstract protected function cacheRead( $URL );

	/**
	 * @abstract
	 */
	abstract public function cacheSave();

	/**
	 * Truncate cache. URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @param null|string|array $URL
	 */
	abstract public function cacheTruncate( $URL=null );

	/**
	 * @return bool
	 */
	abstract public function getCacheLoaded();


	/**
	 * @param string $step_ID
	 * @param Mvc_Layout_OutputPart $output_part
	 *
	 */
	abstract public function addCacheOutputPart( $step_ID, Mvc_Layout_OutputPart $output_part );

	/**
	 * @param string $step_ID
	 *
	 * @return array|Mvc_Layout_OutputPart[]
	 */
	abstract public function getCacheOutputParts( $step_ID );

	/**
	 *
	 * @param $output
	 */
	abstract public function setCacheOutput( $output );

	/**
	 * @return null|string
	 */
	abstract public function getCacheOutput();

	/**
	 * @abstract
	 * @return mixed
	 */
	abstract function helper_mapCache_getCreateCommand();

	/**
	 * @abstract
	 */
	abstract function helper_mapCache_create();

	/**
	 * @abstract
	 * @return mixed
	 */
	abstract function helper_cache_getCreateCommand();

	/**
	 * @abstract
	 */
	abstract function helper_cache_create();

}