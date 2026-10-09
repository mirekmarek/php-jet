<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Http_Headers;
use Jet\Http_Request;
use Jet\Locale;
use Jet\Logger;
use Jet\MVC;
use Jet\MVC_Base_Interface;
use Jet\MVC_Page_Interface;
use Jet\MVC_Router;
use Jet\Auth;
use Jet\SysConf_Jet_ErrorPages;
use Jet\SysConf_Jet_Form;
use Jet\SysConf_Jet_UI;
use Jet\Tr;

/**
 *
 */
class Application_Admin
{
	public static function getBaseId(): string
	{
		return 'admin';
	}

	public static function getBase(): MVC_Base_Interface
	{
		return MVC::getBase( static::getBaseId() );
	}

	public static function getHomePage(): MVC_Page_Interface
	{
		return MVC::getHomePage(
			locale: Application_Admin::getBase()->getDefaultLocale(),
			base_id: Application_Admin::getBaseId(),
		);
	}
	
	public static function init( MVC_Router $router ): void
	{
		static::handleLocaleSwitch( $router );
		
		/** @phpstan-ignore argument.type */
		Logger::setLogger( Application_Service_Admin::Logger() );
		/** @phpstan-ignore argument.type */
		Auth::setController( Application_Service_Admin::AuthController() );

		SysConf_Jet_UI::setViewsDir( $router->getBase()->getViewsPath() . 'ui/' );
		SysConf_Jet_Form::setDefaultViewsDir( $router->getBase()->getViewsPath() . 'form/' );
		SysConf_Jet_ErrorPages::setErrorPagesDir( $router->getBase()->getPagesDataPath( $router->getLocale() ) );
	}
	
	/**
	 * @return Locale[]
	 */
	public static function getAvlLovales() : array
	{
		$base = static::getBase();
		$_avl = $base->getLocalizedData( $base->getDefaultLocale() )->getParameter('avl_locales', default_value: $base->getDefaultLocale()->toString() );
		$_avl = explode(',', $_avl);
		
		$avl = [];
		
		foreach( $_avl as $locale_str ) {
			$locale = new Locale( $locale_str );
			$avl[$locale_str] = $locale;
		}
		
		return $avl;
	}
	
	protected static function handleLocaleSwitch( MVC_Router $router ): void
	{
		$base = static::getBase();
		$avl_locales = static::getAvlLovales();
		$default_locale = array_values(static::getAvlLovales())[0];
		
		$cookie_name = 'adm_locale';
		
		$setCookie = function( Locale $locale ) use ($cookie_name, $base) : void {
			$URL = 'https://'.$base->getLocalizedData( $base->getDefaultLocale() )->getDefaultURL();
			
			$URL = parse_url( $URL );
			
			setcookie(
				name: $cookie_name,
				value: $locale->toString(),
				expires_or_options: time()+(86400*365*10),
				path: '/', //$URL['path'],
				domain: ($URL['host']??'')
			);
			$_COOKIE[$cookie_name] = $locale->toString();
		};
		
		
		
		if(
			!isset($_COOKIE[$cookie_name]) ||
			!isset($avl_locales[$_COOKIE[$cookie_name]])
		) {
			
			$setCookie( $default_locale );
		}
		
		$GET = Http_Request::GET();
		if( $GET->exists( 'set_locale' ) ) {
			$selected_locale = $GET->getString('set_locale', default_value:  $default_locale->toString(), valid_values: array_keys($avl_locales));
			$selected_locale = new Locale( $selected_locale );
			
			$setCookie( $selected_locale );
			
			Http_Headers::reload( unset_GET_params: ['set_locale'] );
		}
		
		$selected_locale_str = $_COOKIE[$cookie_name];
		$selected_locale = new Locale($selected_locale_str);
		
		Locale::setCurrentLocale( $selected_locale );
		Tr::setCurrentLocale( $selected_locale );
		//$router->setLocale( $selected_locale );
	}
	
	
}