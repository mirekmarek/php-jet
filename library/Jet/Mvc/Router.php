<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Mvc_Router extends BaseObject  implements Mvc_Router_Interface
{
	/**
	 * @var bool
	 */
	protected $set_mvc_state = true;

	/**
	 * @var callable
	 */
	protected $after_site_resolved;

	/**
	 * @var callable
	 */
	protected $after_page_resolved;

	/**
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

	/**
	 * @var Mvc_Site_Interface
	 */
	protected $site;

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @var Mvc_Page
	 */
	protected $page;


	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected $file_path = '';

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
	protected $login_required = false;

	/**
	 * @param callable $after_site_resolved
	 */
	public function afterSiteResolved( callable $after_site_resolved )
	{
		$this->after_site_resolved = $after_site_resolved;
	}

	/**
	 * @param callable $after_page_resolved
	 */
	public function afterPageResolved( callable $after_page_resolved )
	{
		$this->after_page_resolved = $after_page_resolved;
	}



	/**
	 *
	 *
	 * @param string $request_URL
	 *
	 * @return void
	 *
	 * @throws Mvc_Router_Exception
	 */
	public function resolve( $request_URL )
	{

		if( !$request_URL ) {
			throw new Mvc_Router_Exception(
				'URL is not defined',
				Mvc_Router_Exception::CODE_URL_NOT_DEFINED
			);
		}

		$this->request_URL = $request_URL;

		$this->parsed_request_URL = Http_URL::parseRequestURL( $request_URL );

		if( !$this->parsed_request_URL->getIsValid() ) {
			throw new Mvc_Router_Exception(
				'Unable to parse URL',
				Mvc_Router_Exception::CODE_UNABLE_TO_PARSE_URL
			);
		}

		$this->is_SSL_request = $this->parsed_request_URL->getIsSSL();

		$this->path_fragments = explode( '/', $this->parsed_request_URL->getPath() );

		array_shift( $this->path_fragments );

		if( !$this->validateURIFormat() ) {
			return;
		}

		if( !$this->resolveSiteAndLocale() ) {
			return;
		}
		if($this->after_site_resolved) {
			$after = $this->after_site_resolved;
			$after( $this );
		}

		if( !$this->resolvePage() ) {
			return;
		}

		$this->resolveAuthentication();

		if($this->after_page_resolved) {
			$after = $this->after_page_resolved;
			$after( $this );
		}

	}

	/**
	 * Validated the URI path format. Returns true if the format is OK and the redirect is not needed.
	 *
	 * - last char in URI path must be / ( ... or some document. example: .html )
	 *
	 * @return bool
	 */
	protected function validateURIFormat()
	{

		$end_i = count( $this->path_fragments )-1;

		$base_URL = $this->parsed_request_URL->getScheme().'://'.$this->parsed_request_URL->getHost();
		if( $this->parsed_request_URL->getPort() ) {
			$base_URL .= ':'.$this->parsed_request_URL->getPort();
		}


		//last char in URI path must be /
		if( $this->path_fragments[$end_i]==='' ) {

			$this->request_URL = $base_URL.'/'.implode( '/', $this->path_fragments );

			unset( $this->path_fragments[$end_i] );

			return true;
		}

		//... or some opened document, or XML and so on
		if( strpos( $this->path_fragments[$end_i], '.' )!==false ) {
			$this->request_URL = $base_URL.'/'.implode( '/', $this->path_fragments );

			return true;
		}


		$this->setIsRedirect(
			$base_URL
			.$this->parsed_request_URL->getPath().'/'
			.(
				$this->parsed_request_URL->getQuery() ?
					'?'.$this->parsed_request_URL->getQuery()
					:
					''
			),

			Http_Headers::CODE_301_MOVED_PERMANENTLY
		);

		return false;
	}

	/**
	 * @return bool
	 */
	protected function resolveSiteAndLocale()
	{
		$site_i = Mvc_Factory::getSiteInstance();

		$site_URLs_map = $site_i->getUrlsMap();

		$known_URLs = array_keys( $site_URLs_map );

		usort(
			$known_URLs,
			function( $a, $b ) {
				return strlen( $b )-strlen( $a );
			}
		);


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

			if( $this->path_fragments ) {
				$slashes_count = substr_count( $URL, '/' )-2;

				if( $slashes_count ) {
					$path_part = array_slice( $this->path_fragments, 0, $slashes_count );
					$path_part = implode( '/', $path_part );

					$current_compare .= '/'.$path_part;
				}
			}

			if( $current_compare==$URL ) {
				$current_site_URL = $site_URLs_map[$URL];
				if( $slashes_count ) {
					$this->path_fragments = array_slice( $this->path_fragments, $slashes_count );
				}
				break;
			}

		}

		if( !$current_site_URL ) {
			$this->setIs404();

			return false;
		}

		$this->is_SSL_request = $current_site_URL->getIsSSL();

		$this->site = Mvc_Site::get( $current_site_URL->getSiteId() );
		$this->locale = $current_site_URL->getLocale();
		if($this->set_mvc_state) {
			Mvc::setCurrentSite($this->site);
			Mvc::setCurrentLocale($this->locale);
		}



		if( !$current_site_URL->getIsDefault() ) {

			$this->setIsRedirect(
				$this->getSite()->getDefaultURL( $this->getLocale() )
				.implode( '/', $this->path_fragments )
				.( $this->path_fragments ? '/' : '' )
				.$this->parsed_request_URL->getQuery()
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
	protected function resolvePage()
	{
		$path = $this->path_fragments;

		$URIs = [];
		for( $i = count( $this->path_fragments ); $i>=0; $i-- ) {

			if( $i>0 ) {
				$URI = '/'.implode( '/', $path ).'/';
				unset( $path[count( $path )-1] );
			} else {
				$URI = '/';
			}

			$URIs[] = $URI;
		}


		$page_i = Mvc_Factory::getPageInstance();

		$page = null;
		foreach( $URIs as $i => $URI ) {
			$page = $page_i->getByRelativeURI( $this->getSite(), $this->getLocale(), $URI );
			if( $page ) {
				if( $i ) {
					$this->path_fragments = array_slice( $this->path_fragments, -1*$i );
				} else {
					$this->path_fragments = [];
				}

				break;
			}
		}

		if( !$page ) {
			throw new Mvc_Router_Exception( 'Failed to find page ...' );
		}

		$this->page = $page;
		if($this->set_mvc_state) {
			Mvc::setCurrentPage($this->page);
		}

		if(
			$page->getSSLRequired() &&
			!$this->is_SSL_request
		) {
			$this->setIsRedirect(
				$page->getSslURL(
					Http_Request::GET()->getRawData(),
					$this->path_fragments
				)
			);

			return false;

		}



		if( $this->path_fragments ) {
			if( !$this->getPage()->parseRequestURL() ) {
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
	protected function resolveAuthentication()
	{

		if( !$this->getPage()->getIsAdminUI()&&!$this->getPage()->getIsSecretPage() ) {
			return true;
		}

		if( Auth::isUserLoggedIn() ) {
			return true;
		}

		$this->login_required = true;

		return false;

	}

	/**
	 * @return bool
	 */
	public function getSetMvcState()
	{
		return $this->set_mvc_state;
	}

	/**
	 * @param bool $set_mvc_state
	 */
	public function setSetMvcState( $set_mvc_state )
	{
		$this->set_mvc_state = $set_mvc_state;
	}

	/**
	 * @return string
	 */
	public function getRequestURL()
	{
		return $this->request_URL;
	}

	/**
	 * @return Http_URL
	 */
	public function getParsedRequestURL()
	{
		return $this->parsed_request_URL;
	}

	/**
	 * @return bool
	 */
	public function getIsSSLRequest()
	{
		return $this->is_SSL_request;
	}


	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param string $file_path
	 */
	public function setIsFile( $file_path )
	{
		$this->file_path = $file_path;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIsFile()
	{
		return (bool)$this->file_path;
	}

	/**
	 * @return string
	 */
	public function getFilePath()
	{
		return $this->file_path;
	}

	/**
	 * Returns true is request is unknown page
	 *
	 * @return bool
	 */
	public function getIs404()
	{
		return $this->is_404;
	}

	/**
	 * Sets the request is unknown page
	 *
	 */
	protected function setIs404()
	{
		$this->is_404 = true;
	}

	/**
	 * @return bool
	 */
	public function getIsRedirect()
	{
		return $this->is_redirect;
	}

	/**
	 * Sets the redirect. Redirection is not performed immediately
	 *
	 * @param string $target_URL
	 * @param string $http_code (optional), options: temporary, permanent, default: Http_Headers::CODE_302_MOVED_TEMPORARY
	 */
	protected function setIsRedirect( $target_URL, $http_code = null )
	{
		if( !$http_code ) {
			$http_code = Http_Headers::CODE_302_MOVED_TEMPORARY;
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = $http_code;
	}

	/**
	 * @return string
	 */
	public function getRedirectTargetURL()
	{
		return $this->redirect_target_URL;
	}

	/**
	 * @return string
	 */
	public function getRedirectType()
	{
		return $this->redirect_type;
	}


	/**
	 * @return bool
	 */
	public function getLoginRequired()
	{
		return $this->login_required;
	}

	/**
	 * @param bool $login_required
	 */
	protected function setLoginRequired( $login_required )
	{
		$this->login_required = $login_required;
	}

	/**
	 * @return array
	 */
	public function shiftPathFragments()
	{
		array_shift( $this->path_fragments );

		return $this->path_fragments;
	}

	/**
	 * @param string $template (example: 'page:%VAL%' )
	 * @param mixed  $default_value
	 * @param int    $fragment_index (optional, default: 0)
	 *
	 * @return int
	 */
	public function parsePathFragmentIntValue( $template, $default_value = null, $fragment_index = 0 )
	{

		$value = $this->parsePathFragmentValue( $template, $fragment_index, '[0-9]{1,}' );

		if( $value===null ) {
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
	public function parsePathFragmentValue( $template, $fragment_index, $reg_exp_part )
	{
		$path_fragments = $this->getPathFragments();

		$value = null;

		if( isset( $path_fragments[$fragment_index] ) ) {
			if( strpos( $template, '%VAL%' )===false ) {
				throw new Exception( 'Incorrect parameter template format. Example: \'page:%VAL%\'' );
			}

			$regexp = '/^'.str_replace( '%VAL%', '('.$reg_exp_part.')', $template ).'$/';

			$matches = [];
			if( preg_match( $regexp, $path_fragments[$fragment_index], $matches ) ) {
				$value = $matches[1];
			}
		}

		return $value;

	}

	/**
	 *
	 * /**
	 * @return array
	 */
	public function getPathFragments()
	{
		return $this->path_fragments;
	}


}