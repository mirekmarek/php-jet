<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
	public static function runMVC( ?string $URL = null ): void
	{
		Debug_Profiler::blockStart( 'MVC router - Init and resolve' );

		$router = MVC::getRouter();

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

		$base = $router->getBase();
		$locale = $router->getLocale();
		$page = $router->getPage();


		if( !$base->getIsActive() ) {
			ErrorPages::handleServiceUnavailable( false );
			return;
		}

		if( !$base->getLocalizedData( $locale )->getIsActive() ) {
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
			Http_Headers::movedPermanently( Http_Request::URL( force_SSL: true ) );
		}


		if( $router->getLoginRequired() ) {
			Auth::handleLogin();
			return;
		}

		if( $router->getAccessNotAllowed() ) {
			ErrorPages::handleUnauthorized( false );

			return;
		}

		$result = $page->render();

		$page->handleHttpHeaders();

		echo $result;

	}

}