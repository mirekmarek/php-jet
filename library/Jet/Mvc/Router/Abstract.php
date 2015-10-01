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
	 *
	 * @var Mvc_Router_Config_Abstract
	 */
	protected $_config;

	/**
	 * @var bool
	 */
	protected $cache_enabled;


    /**
     * @param Mvc_Router_Config_Abstract $config
     */
    public function setConfig(Mvc_Router_Config_Abstract $config)
    {
        $this->_config = $config;
    }



    /**
     * @return Mvc_Router_Config_Abstract
     */
    public function getConfig()
    {
        if(!$this->_config) {
            $this->_config = Mvc_Factory::getRouterConfigInstance();
        }

        return $this->_config;
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
	 * Initializes the router.
	 *
	 * @see Mvc/readme.txt
	 *
	 * @abstract
	 * @param string $request_URL
	 * @param bool|null $cache_enabled (optional, default: by configuration)
	 *
	 * @throws Mvc_Router_Exception
	 */
	abstract public function initialize( $request_URL, $cache_enabled=null );


	/**
	 * @abstract
	 *
	 * @param string $public_file_name
	 */
	abstract public function setIsFile( $public_file_name );

	/**
	 * @abstract
	 *
	 * @return bool
	 */
	abstract public function getIsFile();

    /**
     * @return string
     */
    abstract public function getFileName();


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
	 * @param string $http_code (optional), options: temporary, permanent, default: Mvc_Router::REDIRECT_TYPE_TEMPORARY
	 */
	abstract public function setIsRedirect( $target_URL, $http_code=null );


	/**
	 * Redirect if needed
	 * @abstract
	 */
	abstract public function handleRedirect();

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
	 * @return bool
	 */
	abstract public function getIsSSLRequest();

	/**
	 *
	 * @return Mvc_Router_Cache_Backend_Abstract
	 */
	protected function getCacheBackendInstance() {
		$backend_type = $this->getConfig()->getCacheBackendType();
		return Mvc_Factory::getRouterCacheBackendInstance( $backend_type, Mvc_Factory::getRouterCacheBackendConfigInstance($backend_type) );
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
	 * @abstract
	 * @return mixed
	 */
	abstract function helper_cache_getCreateCommand();

	/**
	 * @abstract
	 */
	abstract function helper_cache_create();

}