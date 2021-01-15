<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

require_once 'Router/Interface.php';

/**
 *
 */
class Mvc_Router extends BaseObject implements Mvc_Router_Interface
{
	/**
	 * @var bool
	 */
	protected bool $set_mvc_state = true;

	/**
	 *
	 * @var string
	 */
	protected string $request_URL = '';

	/**
	 * @var ?Mvc_Site_Interface
	 */
	protected ?Mvc_Site_Interface $site = null;

	/**
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;

	/**
	 * @var ?Mvc_Page_Interface
	 */
	protected ?Mvc_Page_Interface $page = null;

	/**
	 *
	 * @var string
	 */
	protected string $path = '';

	//------------------------------------------------------------------

	/**
	 * @var string
	 */
	protected string $used_path = '';


	//------------------------------------------------------------------
	/**
	 * @var bool
	 */
	protected bool $is_404 = false;

	//------------------------------------------------------------------

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var bool
	 */
	protected bool $is_redirect = false;

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 *
	 * @var string
	 */
	protected string $redirect_target_URL = '';

	/**
	 * @see Mvc_Router_RoutingData::setRedirect()
	 * Options: Mvc_Router::REDIRECT_TYPE_TEMPORARY, Mvc_Router::REDIRECT_TYPE_PERMANENTLY
	 *
	 * @var string|int
	 */
	protected string|int $redirect_type = '';


	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected bool $login_required = false;

	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected bool $has_unused_path = false;

	/**
	 * @var string
	 */
	protected string $valid_url = '';

	/**
	 * @return bool
	 */
	public function getIsSSLRequest(): bool
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
	public function resolve( string|null $request_URL = null ): void
	{

		if( !$request_URL ) {
			$request_URL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		if( ($pos = strpos( $request_URL, '?' )) !== false ) {
			$request_URL = substr( $request_URL, 0, $pos );
		}

		if( substr( $request_URL, 0, 7 ) == 'http://' ) {
			$request_URL = substr( $request_URL, 7 );
		}
		if( substr( $request_URL, 0, 8 ) == 'https://' ) {
			$request_URL = substr( $request_URL, 8 );
		}

		$this->request_URL = (string)$request_URL;

		if( $this->resolve_seekSiteAndLocale() ) {
			$this->resolve_seekPage();

			if( $this->resolve_handleAuthentication() ) {

				if( $this->resolve_pageResolve() ) {
					if( $this->getIsRedirect() ) {
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
	protected function resolve_seekSiteAndLocale(): bool
	{

		Debug_Profiler::blockStart( 'Resolve site and locale' );

		$site_class_name = Mvc_Factory::getSiteInstance();


		Debug_Profiler::blockStart( 'Seeking for site' );
		$site_URLs_map = $site_class_name::getUrlMap();

		$current_site_URL = null;
		$founded_url = null;

		foreach( $site_URLs_map as $URL => $d ) {

			if( substr( $this->request_URL . '/', 0, strlen( $URL ) ) == $URL ) {

				$this->site = $site_class_name::get( $d[0] );
				$this->locale = new Locale( $d[1] );

				$founded_url = $URL;

				$this->path = substr( $this->request_URL, strlen( $founded_url ) );
				if( !$this->path ) {
					$this->path = '';
				}

				break;
			}
		}

		Debug_Profiler::blockEnd( 'Seeking for site' );

		if( !$this->site ) {
			$this->site = $site_class_name::getDefaultSite();
			if( !$this->site ) {

				throw new Mvc_Page_Exception(
					'Unable to find default site'
				);

			}

			$this->locale = $this->site->getDefaultLocale();
			if( !$this->locale ) {

				throw new Mvc_Page_Exception(
					'Unable to find default locale (site: ' . $this->site->getId() . ')'
				);
			}

		}


		$OK = true;

		if( $this->set_mvc_state ) {
			Mvc::setCurrentSite( $this->site );
			Mvc::setCurrentLocale( $this->locale );
		}

		if( $founded_url != $this->site->getLocalizedData( $this->locale )->getDefaultURL() ) {

			$redirect_to = (Http_Request::isHttps() ? 'https' : 'http') . '://'
				. $this->getSite()->getLocalizedData( $this->locale )->getDefaultURL()
				. $this->path;

			if( $this->path && Mvc::getForceSlashOnURLEnd() ) {
				$redirect_to .= '/';
			}

			$this->setIsRedirect( $redirect_to );

			Debug_Profiler::message( 'wrong site URL' );

			$OK = false;
		}


		if( $OK ) {
			if( ($site_initializer = $this->site->getInitializer()) ) {
				Debug_Profiler::blockStart( 'Site initializer call' );
				$site_initializer( $this );
				Debug_Profiler::blockEnd( 'Site initializer call' );
			}
		}


		Debug_Profiler::blockEnd( 'Resolve site and locale' );
		return $OK;
	}

	/**
	 *
	 */
	protected function resolve_seekPage(): void
	{


		Debug_Profiler::blockStart( 'Seeking for page' );

		/**
		 * @var Mvc_Page_Interface $page_class_name
		 */
		$page_class_name = Mvc_Factory::getPageClassName();


		Debug_Profiler::blockStart( 'Load page maps' );
		$map = $page_class_name::getRelativePathMap( $this->site, $this->locale );
		Debug_Profiler::blockEnd( 'Load page maps' );


		$relative_URIs = [];

		if( $this->path ) {
			$path = explode( '/', rtrim( $this->path, '/' ) );

			while( $path ) {
				$relative_URIs[] = implode( '/', $path ) . '/';
				unset( $path[count( $path ) - 1] );
			}
		}

		$page_id = Mvc_Page::HOMEPAGE_ID;


		foreach( $relative_URIs as $i => $URI ) {

			if( !isset( $map[$URI] ) ) {
				continue;
			}

			$page_id = $map[$URI];

			$this->path = substr( $this->path, strlen( $URI ) );
			if( !$this->path ) {
				$this->path = '';
			}

			break;
		}

		$this->page = $page_class_name::get( $page_id, $this->locale, $this->site->getId() );

		$this->resolve_decodePath();

		if( $this->set_mvc_state ) {
			Mvc::setCurrentPage( $this->page );
		}

		Debug_Profiler::blockEnd( 'Seeking for page' );

	}

	/**
	 * @return bool
	 */
	protected function resolve_pageResolve(): bool
	{
		Debug_Profiler::blockStart( 'Resolve page' );

		$OK = $this->getPage()->resolve();
		if( !$OK ) {
			$this->setIs404();
		}

		Debug_Profiler::blockEnd( 'Resolve page' );

		return $OK;
	}

	/**
	 *
	 */
	protected function resolve_decodePath(): void
	{
		$path = [];
		if( $this->path ) {
			$_path = explode( '/', $this->path );
			foreach( $_path as $i => $p ) {
				if( $p ) {
					$path[$i] = rawurldecode( $p );
				}
			}
			$this->path = implode( '/', $path );
		}

	}

	/**
	 *
	 */
	protected function resolve_checkPathUsed(): void
	{

		if( $this->path != $this->used_path ) {
			$this->has_unused_path = true;

			$this->valid_url = $this->getPage()->getURL();
			if( $this->used_path ) {
				$this->valid_url .= '/' . $this->used_path;
			}
		}
	}

	/**
	 *
	 * @return bool
	 */
	protected function resolve_handleAuthentication(): bool
	{
		if( !$this->getPage()->getIsSecret() ) {
			return true;
		}

		if( !Auth::checkCurrentUser() ) {
			$this->login_required = true;
			return false;
		}

		if( !$this->getPage()->accessAllowed() ) {
			return false;
		}

		return true;

	}

	/**
	 * @return bool
	 */
	public function getSetMvcState(): bool
	{
		return $this->set_mvc_state;
	}

	/**
	 * @param bool $set_mvc_state
	 */
	public function setSetMvcState( bool $set_mvc_state ): void
	{
		$this->set_mvc_state = $set_mvc_state;
	}

	/**
	 *
	 * @return Mvc_Site_Interface
	 */
	public function getSite(): Mvc_Site_Interface
	{
		return $this->site;
	}

	/**
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->locale;
	}

	/**
	 *
	 * @return Mvc_Page_Interface
	 */
	public function getPage(): Mvc_Page_Interface
	{
		return $this->page;
	}

	/**
	 *
	 * @return bool
	 */
	public function getIs404(): bool
	{
		return $this->is_404;
	}

	/**
	 *
	 */
	protected function setIs404(): void
	{
		$this->is_404 = true;
	}

	/**
	 * @return bool
	 */
	public function getIsRedirect(): bool
	{
		return $this->is_redirect;
	}

	/**
	 *
	 * @param string $target_URL
	 * @param string|int|null $http_code (optional), options: temporary, permanent, default: Http_Headers::CODE_302_MOVED_TEMPORARY
	 */
	protected function setIsRedirect( string $target_URL, string|int|null $http_code = null ): void
	{

		if( $_SERVER['QUERY_STRING'] ) {
			$target_URL .= '?' . $_SERVER['QUERY_STRING'];
		}

		if( !$http_code ) {
			$http_code = Http_Headers::CODE_302_MOVED_TEMPORARY;
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = (string)$http_code;
	}

	/**
	 * @return string
	 */
	public function getRedirectTargetURL(): string
	{
		return $this->redirect_target_URL;
	}

	/**
	 * @return string
	 */
	public function getRedirectType(): string
	{
		return $this->redirect_type;
	}


	/**
	 * @return bool
	 */
	public function getLoginRequired(): bool
	{
		return $this->login_required;
	}

	/**
	 * @param bool $login_required
	 */
	protected function setLoginRequired( bool $login_required ): void
	{
		$this->login_required = $login_required;
	}


	/**
	 *
	 * /**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getUsedPath(): string
	{
		return $this->used_path;
	}

	/**
	 * @param string $used_path
	 */
	public function setUsedPath( string $used_path ): void
	{
		$this->used_path = $used_path;
	}

	/**
	 * @return bool
	 */
	public function getHasUnusedPath(): bool
	{
		return $this->has_unused_path;
	}

	/**
	 * @return string
	 */
	public function getValidUrl(): string
	{
		return $this->valid_url;
	}

}