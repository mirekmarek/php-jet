<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

require_once 'Router/Interface.php';

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
	 *
	 * @var string
	 */
	protected $request_URL = '';

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

	/**
	 *
	 * @var string
	 */
	protected $path = '';

	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected $used_path = '';

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

	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected $has_unused_path = '';

	/**
	 * @var string
	 */
	protected $valid_url = '';

	/**
	 * @return bool
	 */
	public function getIsSSLRequest()
	{
		return Http_Request::isHttps();
	}

	/**
	 *
	 *
	 * @param string|null $request_URL
	 *
	 * @throws Mvc_Page_Exception
	 */
	public function resolve( $request_URL=null )
	{

		if( !$request_URL ) {
			$request_URL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}

		if(($pos=strpos($request_URL, '?'))!==false) {
			$request_URL = substr($request_URL, 0, $pos);
		}

		if(substr($request_URL, 0, 7)=='http://') {
			$request_URL = substr($request_URL, 7);
		}
		if(substr($request_URL, 0, 8)=='https://') {
			$request_URL = substr($request_URL, 8);
		}

		$this->request_URL = $request_URL;


		if( $this->resolve_seekSiteAndLocale() ) {
			$this->resolve_seekPage();

			if($this->resolve_handleAuthentication()) {
				$this->resolve_decodePath();

				if($this->resolve_pageResolve()) {
					if(
						$this->getIsFile() ||
						$this->getIsRedirect()
					) {
						return;
					}

					$this->resolve_checkPathUsed();
				}
			}
		}


	}


	/**
	 * @return bool
	 *
	 * @throws Mvc_Page_Exception
	 */
	protected function resolve_seekSiteAndLocale()
	{

		Debug_Profiler::blockStart('Resolve site and locale');

		$site_class_name = Mvc_Factory::getSiteInstance();

		Debug_Profiler::blockStart('Load sites');
		$site_class_name::loadSites();
		Debug_Profiler::blockEnd('Load sites');


		Debug_Profiler::blockStart('Seeking for site');
		$site_URLs_map = $site_class_name::getUrlMap();

		$known_URLs = array_keys( $site_URLs_map );

		usort(
			$known_URLs,
			function( $a, $b ) {
				return strlen( $b )-strlen( $a );
			}
		);


		$current_site_URL = null;

		$founded_url = null;

		foreach( $known_URLs as $URL ) {

			if(substr($this->request_URL.'/', 0, strlen($URL))==$URL) {
				$d = $site_URLs_map[$URL];
				$this->site = $d->getSite();
				$this->locale = $d->getLocale();

				$founded_url = $URL;

				$this->path = substr($this->request_URL, strlen($founded_url));
				if(!$this->path) {
					$this->path = '';
				}

				break;
			}
		}

		Debug_Profiler::blockEnd('Seeking for site');

		if(!$this->site) {
			$this->site = $site_class_name::getDefaultSite();
			if(!$this->site) {

				throw new Mvc_Page_Exception(
					'Unable to find default site'
				);

			}

			$this->locale = $this->site->getDefaultLocale();
			if(!$this->locale) {

				throw new Mvc_Page_Exception(
					'Unable to find default locale (site: '.$this->site->getId().')'
				);
			}

		}


		$OK = true;

		if($this->set_mvc_state) {
			Mvc::setCurrentSite($this->site);
			Mvc::setCurrentLocale($this->locale);
		}

		if( $founded_url!=$this->site->getLocalizedData($this->locale)->getDefaultURL() ) {

			$redirect_to = (Http_Request::isHttps() ? 'https' : 'http').'://'
					.$this->getSite()->getLocalizedData($this->locale)->getDefaultURL()
					.$this->path;

			if($this->path && Mvc::getForceSlashOnURLEnd()) {
				$redirect_to .= '/';
			}

			$this->setIsRedirect( $redirect_to );

			Debug_Profiler::message('wrong site URL');

			$OK = false;
		}


		if( $OK ) {
			if( ($site_initializer=$this->site->getInitializer()) ) {
				Debug_Profiler::blockStart('Site initializer call');
				$site_initializer( $this );
				Debug_Profiler::blockEnd('Site initializer call');
			}
		}



		Debug_Profiler::blockEnd('Resolve site and locale');
		return $OK;
	}

	/**
	 *
	 */
	protected function resolve_seekPage()
	{
		Debug_Profiler::blockStart('Seeking for page');

		/**
		 * @var Mvc_Page_Interface $page_class_name
		 */
		$page_class_name = Mvc_Factory::getPageClassName();


		Debug_Profiler::blockStart('Load pages');
		$page_class_name::loadPages( $this->getSite(), $this->getLocale() );
		Debug_Profiler::blockEnd('Load pages');


		$relative_URIs = [];

		if($this->path) {
			$path = explode('/', $this->path);

			while($path) {
				$relative_URIs[] = implode( '/', $path );
				unset( $path[count( $path )-1] );
			}
		}



		foreach( $relative_URIs as $i => $URI ) {

			$this->page = $page_class_name::getByRelativePath( $this->getSite(), $this->getLocale(), $URI );

			if( $this->page ) {

				$this->path = substr($this->path, strlen($URI)+1);
				if(!$this->path) {
					$this->path = '';
				}

				break;
			}
		}


		if( !$this->page ) {
			$this->page = $this->site->getHomepage($this->locale);
		}

		if($this->set_mvc_state) {
			Mvc::setCurrentPage($this->page);
		}

		Debug_Profiler::blockEnd('Seeking for page');

	}

	/**
	 * @return bool
	 */
	protected function resolve_pageResolve()
	{
		Debug_Profiler::blockStart('Resolve page');

		$OK = $this->getPage()->resolve();
		if( !$OK ) {
			$this->setIs404();
		}

		Debug_Profiler::blockEnd('Resolve page');

		return $OK;
	}

	/**
	 *
	 */
	protected function resolve_decodePath()
	{
		$path = [];
		if($this->path) {
			$_path = explode('/', $this->path);
			foreach( $_path as $i=>$p ) {
				if($p) {
					$path[$i] = rawurldecode($p);
				}
			}
			$this->path = implode('/', $path);
		}

	}

	/**
	 *
	 */
	protected function resolve_checkPathUsed()
	{

		if( $this->path!=$this->used_path ) {
			$this->has_unused_path = true;

			$this->valid_url = $this->getPage()->getURL();
			if($this->used_path) {
				$this->valid_url .= '/'.$this->used_path;
			}
		}
	}

	/**
	 *
	 * @return bool
	 */
	protected function resolve_handleAuthentication()
	{
		if( !$this->getPage()->getIsSecret() ) {
			return true;
		}

		if( !Auth::checkCurrentUser() ) {
			$this->login_required = true;
			return false;
		}

		if(!$this->getPage()->accessAllowed()) {
			return false;
		}

		return true;

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
	 *
	 * @param string $target_URL
	 * @param string|null $http_code (optional), options: temporary, permanent, default: Http_Headers::CODE_302_MOVED_TEMPORARY
	 */
	protected function setIsRedirect( $target_URL, $http_code = null )
	{

		if($_SERVER['QUERY_STRING']) {
			$target_URL .= '?'.$_SERVER['QUERY_STRING'];
		}

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
	 *
	 * /**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getUsedPath()
	{
		return $this->used_path;
	}

	/**
	 * @param string $used_path
	 */
	public function setUsedPath( $used_path )
	{
		$this->used_path = $used_path;
	}

	/**
	 * @return bool
	 */
	public function getHasUnusedPath()
	{
		return $this->has_unused_path;
	}

	/**
	 * @return string
	 */
	public function getValidUrl()
	{
		return $this->valid_url;
	}

}