<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Router/Interface.php';

/**
 *
 */
class MVC_Router extends BaseObject implements MVC_Router_Interface
{

	/**
	 * @var bool
	 */
	protected bool $set_system_locale = true;

	/**
	 * @var bool
	 */
	protected bool $set_translator_locale = true;

	/**
	 *
	 * @var string
	 */
	protected string $request_URL = '';

	/**
	 * @var ?MVC_Base_Interface
	 */
	protected ?MVC_Base_Interface $base = null;

	/**
	 * @var ?Locale
	 */
	protected ?Locale $locale = null;

	/**
	 * @var ?MVC_Page_Interface
	 */
	protected ?MVC_Page_Interface $page = null;

	//------------------------------------------------------------------

	/**
	 *
	 * @var string
	 */
	protected string $url_path = '';


	/**
	 * @var string
	 */
	protected string $used_url_path = '';


	//------------------------------------------------------------------
	/**
	 * @var bool
	 */
	protected bool $is_404 = false;

	//------------------------------------------------------------------

	/**
	 *
	 * @var bool
	 */
	protected bool $is_redirect = false;

	/**
	 *
	 * @var string
	 */
	protected string $redirect_target_URL = '';

	/**
	 *
	 * @var int
	 */
	protected int $redirect_type = Http_Headers::CODE_302_MOVED_TEMPORARY;


	//-----------------------------------------------------------------

	/**
	 * @var bool
	 */
	protected bool $login_required = false;

	/**
	 * @var bool
	 */
	protected bool $access_not_allowed = false;

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
	public function getSetSystemLocale(): bool
	{
		return $this->set_system_locale;
	}

	/**
	 * @param bool $set_system_locale
	 */
	public function setSetSystemLocale( bool $set_system_locale ): void
	{
		$this->set_system_locale = $set_system_locale;
	}

	/**
	 * @return bool
	 */
	public function getSetTranslatorLocale(): bool
	{
		return $this->set_translator_locale;
	}

	/**
	 * @param bool $set_translator_locale
	 */
	public function setSetTranslatorLocale( bool $set_translator_locale ): void
	{
		$this->set_translator_locale = $set_translator_locale;
	}



	/**
	 *
	 *
	 * @param string|null $request_URL
	 *
	 * @throws MVC_Page_Exception
	 */
	public function resolve( string|null $request_URL = null ): void
	{

		if( !$request_URL ) {
			$request_URL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

		if( ($pos = strpos( $request_URL, '?' )) !== false ) {
			$request_URL = substr( $request_URL, 0, $pos );
		}

		if( str_starts_with( $request_URL, 'http://' ) ) {
			$request_URL = substr( $request_URL, 7 );
		}
		if( str_starts_with( $request_URL, 'https://' ) ) {
			$request_URL = substr( $request_URL, 8 );
		}

		$this->request_URL = (string)$request_URL;

		if( $this->resolve_seekBaseAndLocale() ) {
			if($this->resolve_seekPage()) {
				if($this->resolve_authorizePage()) {
					if($this->resolve_pageResolve()) {
						$this->resolve_checkUrlPathUsed();
					}
				}
			}
		}
	}


	/**
	 * @return bool
	 *
	 * @throws MVC_Page_Exception
	 */
	protected function resolve_seekBaseAndLocale(): bool
	{

		Debug_Profiler::blockStart( 'Resolve base and locale' );

		$base_class_name = Factory_MVC::getBaseInstance();


		Debug_Profiler::blockStart( 'Seeking for base' );
		$base_URLs_map = $base_class_name::_getUrlMap();

		$founded_url = null;

		foreach( $base_URLs_map as $URL => $d ) {

			if( str_starts_with( $this->request_URL . '/', $URL ) ) {

				$this->base = $base_class_name::_get( $d[0] );
				$this->locale = new Locale( $d[1] );

				$founded_url = $URL;

				$this->url_path = substr( $this->request_URL, strlen( $founded_url ) );
				if( !$this->url_path ) {
					$this->url_path = '';
				}

				break;
			}
		}

		Debug_Profiler::blockEnd( 'Seeking for base' );

		if( !$this->base ) {
			$this->base = MVC::getDefaultBase();
			if( !$this->base ) {

				throw new MVC_Page_Exception(
					'Unable to find default base'
				);

			}

			$this->locale = $this->base->getDefaultLocale();
			if( !$this->locale ) {

				throw new MVC_Page_Exception(
					'Unable to find default locale (base: ' . $this->base->getId() . ')'
				);
			}

		}


		$OK = true;

		if($this->set_system_locale) {
			Locale::setCurrentLocale( $this->locale );
		}
		if($this->set_translator_locale) {
			Translator::setCurrentLocale( $this->locale );
		}

		if( $founded_url != $this->base->getLocalizedData( $this->locale )->getDefaultURL() ) {

			$redirect_to = (Http_Request::isHttps() ? 'https' : 'http') . '://'
				. $this->getBase()->getLocalizedData( $this->locale )->getDefaultURL()
				. $this->url_path;

			if( $this->url_path && SysConf_Jet_MVC::getForceSlashOnURLEnd() ) {
				$redirect_to .= '/';
			}

			$this->setIsRedirect( $redirect_to );

			Debug_Profiler::message( 'wrong base URL' );

			$OK = false;
		}


		if( $OK ) {
			if( ($base_initializer = $this->base->getInitializer()) ) {
				Debug_Profiler::blockStart( 'Base initializer call' );
				$base_initializer( $this );
				Debug_Profiler::blockEnd( 'Base initializer call' );
			}
		}


		Debug_Profiler::blockEnd( 'Resolve base and locale' );
		return $OK;
	}

	/**
	 *
	 */
	protected function resolve_seekPage(): bool
	{


		Debug_Profiler::blockStart( 'Seeking for page' );

		/**
		 * @var MVC_Page_Interface $page_class_name
		 */
		$page_class_name = Factory_MVC::getPageClassName();


		Debug_Profiler::blockStart( 'Load page maps' );
		$map = $page_class_name::_getRelativePathMap( $this->base, $this->locale );
		Debug_Profiler::blockEnd( 'Load page maps' );


		$relative_URIs = [];

		if( $this->url_path ) {
			$path = explode( '/', rtrim( $this->url_path, '/' ) );

			while( $path ) {
				$relative_URIs[] = implode( '/', $path ) . '/';
				unset( $path[count( $path ) - 1] );
			}
		}

		$page_id = MVC::HOMEPAGE_ID;


		foreach( $relative_URIs as $URI ) {

			if( !isset( $map[$URI] ) ) {
				continue;
			}

			$page_id = $map[$URI];

			$this->url_path = substr( $this->url_path, strlen( $URI ) );
			if( !$this->url_path ) {
				$this->url_path = '';
			}

			break;
		}

		$this->page = $page_class_name::_get( $page_id, $this->locale, $this->base->getId() );

		$this->resolve_decodePath();

		Debug_Profiler::blockEnd( 'Seeking for page' );

		return true;
	}

	/**
	 * @return bool
	 */
	protected function resolve_authorizePage(): bool
	{
		return $this->page->authorize();
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
		if( $this->url_path ) {
			$_path = explode( '/', $this->url_path );
			foreach( $_path as $i => $p ) {
				if( $p ) {
					$path[$i] = rawurldecode( $p );
				}
			}
			$this->url_path = implode( '/', $path );
		}

	}

	/**
	 *
	 */
	protected function resolve_checkUrlPathUsed(): void
	{
		if( $this->getIsRedirect() ) {
			return;
		}

		if( $this->getIs404() ) {
			return;
		}


		if( $this->url_path != $this->used_url_path ) {
			$this->has_unused_path = true;

			$this->valid_url = $this->getPage()->getURL();
			if( $this->used_url_path ) {
				$this->valid_url .= '/' . $this->used_url_path;
			}
		}
	}

	/**
	 *
	 * @return ?MVC_Base_Interface
	 */
	public function getBase(): ?MVC_Base_Interface
	{
		return $this->base;
	}

	/**
	 * @return ?Locale
	 */
	public function getLocale(): ?Locale
	{
		return $this->locale;
	}

	/**
	 *
	 * @return ?MVC_Page_Interface
	 */
	public function getPage(): ?MVC_Page_Interface
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
	public function setIs404(): void
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
	 * @param int $http_code
	 */
	public function setIsRedirect( string $target_URL, int $http_code = Http_Headers::CODE_302_MOVED_TEMPORARY ): void
	{

		if( $_SERVER['QUERY_STRING'] ) {
			$target_URL .= '?' . $_SERVER['QUERY_STRING'];
		}

		$this->is_redirect = true;
		$this->redirect_target_URL = $target_URL;
		$this->redirect_type = $http_code;
	}

	/**
	 * @return string
	 */
	public function getRedirectTargetURL(): string
	{
		return $this->redirect_target_URL;
	}

	/**
	 * @return int
	 */
	public function getRedirectType(): int
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
	 *
	 */
	public function setLoginRequired(): void
	{
		$this->login_required = true;
	}

	/**
	 * @return bool
	 */
	public function getAccessNotAllowed(): bool
	{
		return $this->access_not_allowed;
	}

	/**
	 *
	 */
	public function setAccessNotAllowed(): void
	{
		$this->access_not_allowed = true;
	}


	/**
	 *
	 * /**
	 * @return string
	 */
	public function getUrlPath(): string
	{
		return $this->url_path;
	}

	/**
	 * @return string
	 */
	public function getUsedUrlPath(): string
	{
		return $this->used_url_path;
	}

	/**
	 * @param string $used_path
	 */
	public function setUsedUrlPath( string $used_path ): void
	{
		$this->used_url_path = $used_path;
	}

	/**
	 * @return bool
	 */
	public function getHasUnusedUrlPath(): bool
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