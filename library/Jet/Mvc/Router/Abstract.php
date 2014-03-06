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
 * @JetFactory:class = 'Jet\Mvc_Factory'
 * @JetFactory:method = 'getRouterInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\Mvc_Router_Abstract'
 */
abstract class Mvc_Router_Abstract extends Object {

	/**
	 * @see Mvc/readme.txt
	 *
	 *  Key = URI path fragment
	 *  Value = service type (and controller class!)
	 *
	 * @var array
	 */
	protected static $path_fragments_service_types_map = array(
		'_ajax_' => Mvc_Router::SERVICE_TYPE_AJAX,
		'_rest_' => Mvc_Router::SERVICE_TYPE_REST,
		'_sys_' => Mvc_Router::SERVICE_TYPE_SYS,
		'_JetJS_' => Mvc_Router::SERVICE_TYPE__JETJS_,
	);

	/**
	 * @see Mvc/readme.txt
	 *
	 *  Key = service type (and controller class!)
	 *  Value = URI path fragment
	 *
	 * @var array
	 */
	protected static $service_types_path_fragments_map = array(
		Mvc_Router::SERVICE_TYPE_AJAX => '_ajax_',
		Mvc_Router::SERVICE_TYPE_REST => '_rest_',
		Mvc_Router::SERVICE_TYPE_SYS => '_sys_',
		Mvc_Router::SERVICE_TYPE__JETJS_ => '_JetJS_'
	);


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
	 * @return array
	 */
	public function getPathFragmentsServiceTypesMap() {
		return static::$path_fragments_service_types_map;
	}

	/**
	 * @return array
	 */
	public function getServiceTypesPathFragmentsMap() {
		return static::$service_types_path_fragments_map;
	}


	/**
	 * Initializes the router.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @abstract
	 * @param string $URL
	 * @param bool|null $cache_enabled (optional, default: by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 * @return mixed
	 */
	abstract public function initialize( $URL, $cache_enabled=null );

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
	abstract public function getUIManagerModuleName();

	/**
	 * Returns instance of main site UI module
	 *
	 * @abstract
	 *
	 * @return Mvc_UIManagerModule_Abstract
	 */
	abstract public function getUIManagerModuleInstance();

	/**
	 * @return string
	 */
	abstract public function getAuthManagerModuleName();

	/**
	 * @abstract
	 *
	 * @return Auth_ManagerModule_Abstract
	 */
	abstract public function getAuthManagerModuleInstance();

	/**
	 * Setup error handler if any changes from default settings
	 *
	 * @abstract
	 */
	abstract public function setupErrorHandler();

	/**
	 *
	 * @abstract
	 * @param Auth_ManagerModule_Abstract $auth_manager_module_instance
	 */
	abstract public function setAuthManagerModuleInstance( Auth_ManagerModule_Abstract $auth_manager_module_instance );

	/**
	 *
	 * @abstract
	 */
	abstract public function setupLayout();

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
	abstract public function getURL();

	/**
	 * @return Http_URL
	 */
	abstract public function getParsedURL();

	/**
	 * @abstract
	 * @return array
	 */
	abstract public function getPathFragments();


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
	 *
	 * @param Mvc_Pages_Page_Abstract $page
	 */
	abstract public function setPage( Mvc_Pages_Page_Abstract $page );

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getServiceType();

	/**
	 * @abstract
	 * @return string|null
	 */
	abstract public function getServiceSubtype();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getModuleName();

	/**
	 * @abstract
	 * @return string
	 */
	abstract public function getModuleAction();

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
	abstract public function getSiteScriptsURI();

	/**
	 * @return string
	 */
	abstract public function getSiteStylesURI();

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
	 * @param string $service_subtype
	 */
	abstract public function setServiceSubtype($service_subtype);

	/**
	 * @abstract
	 * @param string $module_name
	 */
	abstract public function setModuleName($module_name);

	/**
	 * @abstract
	 * @param string $module_action
	 */
	abstract public function setModuleAction($module_action);

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
	 * @abstract
	 * @return bool
	 */
	abstract protected function cacheRead();

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
	 * @param string $loop_ID
	 * @param Mvc_Layout_OutputPart $output_part
	 *
	 */
	abstract public function setCacheOutputParts( $loop_ID, Mvc_Layout_OutputPart $output_part );

	/**
	 * @param string $loop_ID
	 *
	 * @return null|Mvc_Layout_OutputPart
	 */
	abstract public function getCacheOutputParts( $loop_ID );

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
	abstract function helper_cache_getCreateCommand();

	/**
	 * @abstract
	 */
	abstract function helper_cache_create();
}