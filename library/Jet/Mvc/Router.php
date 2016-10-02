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
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router extends Mvc_Router_Abstract {


	/**
	 * Request URL
	 *
	 * @var string
	 */
	protected $request_URL = '';

	/**
	 * @var Http_URL
	 */
	protected $parsed_request_URL;

	/**
	 * Request path fragments (http://host/path-fragment-0/path-fragment-1/ )
	 *
	 * @var string[]
	 */
	protected $path_fragments = [];

	/**
	 * Is it SSL (https) request?
	 * @var bool
	 */
	protected $is_SSL_request = false;


	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected $file_name = '';

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
	protected $redirect_target_URL = '';

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 * Options: Mvc_Router::REDIRECT_TYPE_TEMPORARY, Mvc_Router::REDIRECT_TYPE_PERMANENTLY
	 *
	 * @var string
	 */
	protected $redirect_type = '';


	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected $_cache_loaded = false;


	//-----------------------------------------------------------------


	/**
	 * Initializes the router.
	 *
	 *
	 * @param string $request_URL
	 * @param bool|null $cache_enabled (optional, default: by configuration)
	 *
     * @return void
     *
     * @throws Mvc_Router_Exception
	 */
	public function initialize( $request_URL, $cache_enabled=null ) {

		if( !$request_URL ) {
			throw new Mvc_Router_Exception(
				'URL is not defined',
				Mvc_Router_Exception::CODE_URL_NOT_DEFINED
			);
		}

		if($cache_enabled!==null) {
			$this->cache_enabled = (bool)$cache_enabled;
		} else {
			$this->cache_enabled = $this->getConfig()->getCacheEnabled();
		}

		$this->request_URL = $request_URL;


        if($this->cache_enabled) {
            if( $this->cacheRead($this->request_URL) ) {
                return;
            }

            register_shutdown_function( [$this, 'cacheSave']);
        }


		$this->parsed_request_URL = Http_URL::parseRequestURL($request_URL);
		$this->is_SSL_request = $this->parsed_request_URL->getIsSSL();

		if( !$this->parsed_request_URL->getIsValid() ) {
			throw new Mvc_Router_Exception(
				'Unable to parse URL',
				Mvc_Router_Exception::CODE_UNABLE_TO_PARSE_URL
			);
		}

		$this->path_fragments = explode( '/', $this->parsed_request_URL->getPath() );

		array_shift( $this->path_fragments );


		foreach( $this->path_fragments as $i=>$pf ) {
			$this->path_fragments[$i] = rawurldecode( $pf );
		}


		if( !$this->validateURIFormat() ) {
			return;
		}

        if( !$this->resolveSiteAndLocale() ) {
            return;
        }

        if( !$this->resolvePage() ) {
            return;
        }


		$this->resolveAuthentication();
	}


    /**
     * @return bool
     */
    protected function resolveSiteAndLocale() {
        $site_i = Mvc_Factory::getSiteInstance();

        $site_URLs_map = $site_i->getUrlsMap();

        $known_URLs = array_keys($site_URLs_map);

        usort( $known_URLs, function($a,$b){
            return strlen($b)-strlen($a);
        } );


        /**
         * @var Mvc_Site_LocalizedData_URL_Interface $current_site_URL
         */
        $current_site_URL = null;

        $current_host = $this->parsed_request_URL->getScheme().'://'.$this->parsed_request_URL->getHost();
        if( $this->parsed_request_URL->getPort() ) {
            $current_host .= ':'.$this->parsed_request_URL->getPort();
        }


        foreach( $known_URLs as $URL ) {

            $current_compare = $current_host;
            $slashes_count = 0;

            if($this->path_fragments) {
                $slashes_count = substr_count($URL, '/')-2;

                if( $slashes_count ) {
                    $path_part = array_slice($this->path_fragments, 0, $slashes_count);
                    $path_part = implode('/', $path_part);

                    $current_compare .= '/'.$path_part;
                }
            }

            if($current_compare==$URL) {
                $current_site_URL = $site_URLs_map[$URL];
                if($slashes_count) {
                    $this->path_fragments = array_slice($this->path_fragments, $slashes_count);
                }
                break;
            }

        }

        if(!$current_site_URL) {
            $this->setIs404();

            return false;
        }

        $this->is_SSL_request = $current_site_URL->getIsSSL();

        $this->setSite( Mvc_Site::get( $current_site_URL->getSiteID() ) );
        $this->setLocale( $current_site_URL->getLocale() );



        if(!$current_site_URL->getIsDefault() ) {

            $this->setIsRedirect(
                $this->getSite()->getDefaultURL( $this->getLocale() )
                    . implode('/', $this->path_fragments)
                    . ( $this->path_fragments ? '/' : '' )
                    . $this->parsed_request_URL->getQuery()
            );

            return false;
        }

        return true;
    }


    /**
     * @return bool
     *
     * @throws Mvc_Router_Exception
     */
    protected function resolvePage() {
        $path = $this->path_fragments;

        $URIs = [];
        for($i=count($this->path_fragments); $i>=0; $i--) {

            if($i>0) {
                $URI = '/'.implode('/', $path).'/';
                unset($path[count($path)-1]);
            } else {
                $URI = '/';
            }

            $URIs[] =$URI;
        }


        $page_i = Mvc_Factory::getPageInstance();

        $page = null;
        foreach( $URIs as $i=>$URI ) {
            $page = $page_i->getByRelativeURI($this->getSite(), $this->getLocale(), $URI);
            if($page) {
                if($i) {
                    $this->path_fragments = array_slice($this->path_fragments, -1*$i);
                } else {
                    $this->path_fragments = [];
                }

                break;
            }
        }

        if(!$page) {
            throw new Mvc_Router_Exception('Failed to find page ...');
        }

        if($page->getSSLRequired() && !$this->is_SSL_request) {
            $this->setIsRedirect(
                $page->getSslURL( $_GET, $this->path_fragments )
            );

            return false;

        }


        $this->setPage( $page );

        if($this->path_fragments) {
            if(!$this->getPage()->parseRequestURL()) {
                Mvc::unsetCurrentPage();
                $this->setIs404();

                return false;
            }
        }

        return true;

    }

    /**
     *
     * @throws Mvc_Router_Exception
     * @return bool
     */
	protected function resolveAuthentication() {

        if(
            $this->getPage()->getAuthenticationRequired()
        ) {

            $auth_controller = Auth::getCurrentAuthController();

            $this->cache_enabled = false;

            if( $auth_controller->getAuthenticationRequired() ) {

                $this->setPage( $auth_controller->getAuthenticationPage() );
	            $this->getPage()->setAuthenticationRequired(false);
            }

        }


		return true;
	}

	/**
	 * @param string $public_file_name
	 */
	public function setIsFile( $public_file_name ) {
		$this->file_name = $public_file_name;
	}

	/**
	 * @abstract
	 *
	 * @return bool
	 */
	public function getIsFile() {
		return (bool)$this->file_name;
	}

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }


	/**
	 * Sets the request is unknown page
	 *
	 */
	public function setIs404() {
		$this->is_404 = true;
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
	 * Sets the redirect. Redirection is not performed immediately, but after operations such as storage of records to cache and so on.
	 *
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Http_Headers::CODE_302_MOVED_TEMPORARY
	 */
	public function setIsRedirect( $target_URL, $http_code=null ) {
		if(!$http_code ) {
			$http_code = Http_Headers::CODE_302_MOVED_TEMPORARY;
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = $http_code;
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

		if($this->redirect_type==Http_Headers::CODE_301_MOVED_PERMANENTLY) {
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

		$end_i = count($this->path_fragments)-1;

        $base_URL = $this->parsed_request_URL->getScheme().'://'.$this->parsed_request_URL->getHost();
        if($this->parsed_request_URL->getPort()) {
            $base_URL .= ':'.$this->parsed_request_URL->getPort();
        }


		//last char in URI path must be /
		if( $this->path_fragments[$end_i]==='' ) {

			$this->request_URL = $base_URL.'/'.implode('/', $this->path_fragments);

			unset($this->path_fragments[$end_i]);

			return true;
		}

		//... or some opened document, or XML and so on
		if( strpos( $this->path_fragments[$end_i], '.')!==false ) {
			$this->request_URL = $base_URL.'/'.implode('/', $this->path_fragments);

			return true;
		}


		$this->setIsRedirect(
			$base_URL
				. $this->parsed_request_URL->getPath() . '/'
				. (($this->parsed_request_URL->getQuery()) ? '?'.$this->parsed_request_URL->getQuery() : ''),

			Http_Headers::CODE_301_MOVED_PERMANENTLY
		);

		return false;
	}

	/**
	 * @return string
	 */
	public function getRequestURL() {
		return $this->request_URL;
	}

	/**
	 * @return Http_URL
	 */
	public function getParsedRequestURL() {
		return $this->parsed_request_URL;
	}

	/**

	/**
	 * @return array
	 */
	public function getPathFragments() {
		return $this->path_fragments;
	}

	/**
	 * @return array
	 */
	public function shiftPathFragments() {
		array_shift( $this->path_fragments );

		return $this->path_fragments;
	}



	/**
	 * @param string $template  (example: 'page:%VAL%' )
	 * @param mixed $default_value
	 * @param int $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	public function parsePathFragmentIntValue( $template, $default_value=null, $fragment_index=0 ) {

		$value = $this->parsePathFragmentValue($template, $fragment_index, '[0-9]{1,}');

		if($value===null) {
            return $default_value;
		}

		return (int)$value;
	}

	/**
	 * @param string $template
	 * @param string $fragment_index
	 * @param string $reg_exp_part
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part ) {
		$path_fragments = $this->getPathFragments();

		$value = null;

		if(isset($path_fragments[$fragment_index])) {
			if(strpos($template, '%VAL%')===false) {
				throw new Exception('Incorrect parameter template format. Example: \'page:%VAL%\'');
			}

			$regexp = '/^'.str_replace( '%VAL%', '('.$reg_exp_part.')' , $template ).'$/';

			$matches = [];
			if(preg_match( $regexp, $path_fragments[$fragment_index], $matches )) {
				$value = $matches[1];
			}
		}

		return $value;

	}

	/**
	 * @return bool
	 */
	public function getIsSSLRequest() {
		return $this->is_SSL_request;
	}

    /**
     * @param Mvc_Site_Interface $site
     */
    protected function setSite( Mvc_Site_Interface $site)
    {
        Mvc::setCurrentSite( $site );
    }

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
    protected function getSite() {
		return Mvc::getCurrentSite();
	}

    /**
     * @param Locale $locale
     */
    protected function setLocale( Locale $locale)
    {
        Mvc::setCurrentLocale( $locale );
    }



	/**
	 * @return Locale
	 */
    protected function getLocale() {
        return Mvc::getCurrentLocale();
	}


	/**
	 *
	 * @return Mvc_Page_Interface
	 */
    protected function getPage() {
		return Mvc::getCurrentPage();
	}

    /**
     * @param Mvc_Page_Interface $page
     */
    protected function setPage( Mvc_Page_Interface $page ) {
        Mvc::setCurrentPage( $page );
	}

	/**
	 * @param string $URL
	 *
	 * @return bool
	 */
	protected function cacheRead( $URL ) {

		if(!$this->cache_enabled) {
			return false;
		}

		$data = $this->getCacheBackendInstance()->load( $URL );

		if(!$data) {
			return false;
		}
        foreach( get_object_vars($data['router']) as $k=>$v ) {
            $this->{$k} = $v;
        }

        Mvc_Factory::getSiteInstance()->readCachedData( $data );


        Mvc::setCurrentSite( $data['site'] );
        Mvc::setCurrentLocale( $data['locale'] );

        if(isset($data['page'])) {
            Mvc_Factory::getPageInstance()->readCachedData( $data );

            Mvc::setCurrentPage( $data['page'] );
        }

        $this->_cache_loaded = true;

		return true;
	}

	/**
	 *
	 */
	public function cacheSave() {
		if(
            $this->_cache_loaded ||
			!$this->cache_enabled ||
            (
                $this->getPage() &&
                $this->getPage()->getAuthenticationRequired()
            ) ||
            Debug_ErrorHandler::getLastError()
		) {
			return;
		}

        $site = Mvc::getCurrentSite();

        $data = [
            'site' => $site,
            'router' => Mvc::getCurrentRouter(),
            'locale' => Mvc::getCurrentLocale(),
        ];

        $site->writeCachedData( $data );

        $page = Mvc::getCurrentPage();
        if($page) {
            $data['page'] = $page;

            $page->writeCachedData( $data );
        }



		$this->getCacheBackendInstance()->save($this->request_URL, $data);
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
        if($this->getConfig()->getCacheEnabled()) {
            $this->getCacheBackendInstance()->truncate($URL);
        }
	}

	/**
	 * @return bool
	 */
	public function getCacheLoaded() {
		return $this->_cache_loaded;
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