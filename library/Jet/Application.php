<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 *
 */
class Application extends BaseObject
{

	/**
	 * @var bool
	 */
	protected static bool $do_not_end = false;

	/**
	 *
	 */
	public static function end(): void
	{
		if( !static::$do_not_end ) {
			exit();
		}
	}

	/**
	 * Useful for tests
	 *
	 */
	public static function doNotEnd(): void
	{
		static::$do_not_end = true;
	}

	/**
	 * @param ?string $URL
	 */
	public static function runMvc( ?string $URL = null ): void
	{
		Debug_Profiler::blockStart( 'MVC router - Init and resolve' );

		$router = Mvc::getRouter();

		$router->resolve( $URL );

		Debug_Profiler::blockEnd( 'MVC router - Init and resolve' );

		if( $router->getIsRedirect() ) {
			Http_Headers::redirect(
				$router->getRedirectType(),
				$router->getRedirectTargetURL()
			);
		}

		if( $router->getHasUnusedUrlPath() ) {
			Http_Headers::movedPermanently( $router->getValidUrl() );
		}


		if( $router->getIs404() ) {
			ErrorPages::handleNotFound( false );

			return;
		}

		$site = Mvc::getCurrentSite();
		$locale = Mvc::getCurrentLocale();
		$page = Mvc::getCurrentPage();


		if( !$site->getIsActive() ) {
			ErrorPages::handleServiceUnavailable( false );
			return;
		}

		if( !$site->getLocalizedData( $locale )->getIsActive() ) {
			ErrorPages::handleNotFound( false );
			return;
		}

		if( !$page->getIsActive() ) {
			ErrorPages::handleNotFound( false );
			return;
		}

		if(
			$page->getSSLRequired() &&
			!Http_Request::isHttps()
		) {
			Http_Headers::movedPermanently( Http_Request::URL( true, true ) );
		}


		if( $router->getLoginRequired() ) {
			Auth::handleLogin();
			return;
		}

		if( $router->accessNotAllowed() ) {
			ErrorPages::handleUnauthorized( false );

			return;
		}

		$result = $page->render();

		$page->handleHttpHeaders();

		echo $result;

	}

}