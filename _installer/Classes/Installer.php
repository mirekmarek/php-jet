<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Factory_MVC;
use Jet\MVC_Base;
use Jet\MVC_Layout;
use Jet\Locale;
use Jet\MVC_View;
use Jet\SysConf_Jet_Translator;
use Jet\Session;
use Jet\SysConf_URI;
use Jet\Tr;
use Jet\Translator;
use Jet\SysConf_Path;
use JetApplication\Application_Admin;
use JetApplication\Application_Exports;
use JetApplication\Application_REST;
use JetApplication\Application_Services;
use JetApplication\Application_Web;


require 'Step/Controller.php';

/**
 *
 */
class Installer
{

	/**
	 * @var array<string>
	 */
	protected static array $steps = [];

	/**
	 * @var array<string,Installer_Step_Controller>
	 */
	protected static array $step_controllers = [];

	/**
	 * @var array<string,Locale>
	 */
	protected static array $available_installer_locales = [];
	
	/**
	 * @var array<string,Locale>
	 */
	protected static array $available_locales = [];

	/**
	 * @var array<string,Locale>
	 */
	protected static array $selected_locales = [];
	
	/**
	 * @var ?Locale
	 */
	protected static ?Locale $current_locale = null;
	
	/**
	 * @var ?Locale
	 */
	protected static ?Locale $services_locale = null;
	
	
	/**
	 * @var string
	 */
	protected static string $current_step_name = '';

	/**
	 * @var string
	 */
	protected static string $base_path = '';

	/**
	 * @var ?MVC_Layout
	 */
	protected static ?MVC_Layout $layout = null;
	
	protected static ?Session $session = null;

	/**
	 * @param array<string> $steps
	 */
	public static function setSteps( array $steps ): void
	{
		static::$steps = $steps;
		static::$step_controllers = [];
	}

	/**
	 * @return array<string,Locale>
	 */
	public static function getAvailableInstallerLocales(): array
	{
		return self::$available_installer_locales;
	}

	/**
	 * @param array<string> $locales
	 */
	public static function setAvailableInstallerLocales( array $locales ): void
	{
		$ls = [];

		foreach( $locales as $locale ) {
			$locale = new Locale( $locale );
			$ls[(string)$locale] = $locale;
		}

		self::$available_installer_locales = $ls;
	}
	
	/**
	 * @return array<string,Locale>
	 */
	public static function getAvailableLocales(): array
	{
		return self::$available_locales;
	}
	
	/**
	 * @param array<string> $locales
	 */
	public static function setAvailableLocales( array $locales ): void
	{
		$ls = [];
		
		foreach( $locales as $locale ) {
			$locale = new Locale( $locale );
			$ls[(string)$locale] = $locale;
		}
		foreach(Locale::getAllLocalesList( new Locale('en_EU') ) as $locale=>$l_name) {
			if(isset($ls[$locale])) {
				continue;
			}
			
			$locale = new Locale( $locale );
			$ls[(string)$locale] = $locale;
		}
		
		self::$available_locales = $ls;
	}
	
	public static function getServicesLocale():?Locale
	{
		return self::$services_locale;
	}
	
	public static function setServicesLocale( Locale $services_locale ): void
	{
		self::$services_locale = $services_locale;
	}
	
	

	
	
	/**
	 * @return array<string,Locale>
	 */
	public static function getSelectedLocales(): array
	{
		if( !self::$selected_locales ) {
			self::$selected_locales = static::getSession()->getValue( 'selected_locales', [] );
		}

		return self::$selected_locales;
	}

	/**
	 * @param array<Locale> $selected_locales
	 */
	public static function setSelectedLocales( array $selected_locales ): void
	{
		self::$selected_locales = [];

		foreach( $selected_locales as $locale ) {
			$locale = new Locale( $locale );
			
			self::$selected_locales[$locale->toString()] = $locale;
		}

		static::getSession()->setValue( 'selected_locales', self::$selected_locales );
	}

	/**
	 * @return Session
	 */
	public static function getSession(): Session
	{
		if(!static::$session) {
			static::$session = new Session( '_installer_' );
		}
		
		return static::$session;
	}

	/**
	 * @return Locale
	 */
	public static function getCurrentLocale(): Locale
	{
		if( !static::$current_locale ) {
			$session = static::getSession();

			if( $session->getValueExists( 'current_locale' ) ) {
				static::$current_locale = $session->getValue( 'current_locale' );
			} else {
				foreach( static::$available_installer_locales as $locale ) {
					static::setCurrentLocale( $locale );
					break;
				}
			}

		}


		return static::$current_locale;
	}

	/**
	 * @param Locale $locale
	 */
	public static function setCurrentLocale( Locale $locale ): void
	{
		static::getSession()->setValue( 'current_locale', $locale );
		static::$current_locale = $locale;
	}

	/**
	 *
	 */
	public static function main(): void
	{
		Http_Request::initialize( true );

		static::initStepControllers();

		$GET = Http_Request::GET();
		if( $GET->exists( 'step' ) ) {

			$steps = [];
			$first_step = null;
			foreach( static::$step_controllers as $controller ) {
				if( !$first_step ) {
					$first_step = $controller->getName();
				}
				if( $controller->getIsFuture() ) {
					break;
				}
				$steps[] = $controller->getName();
			}

			$step = $GET->getString( 'step', $first_step, $steps );

			static::setCurrentStepName( $step );
			Http_Headers::movedTemporary( '?' );

		}

		static::initTranslator();

		static::getCurrentStepController()->main();

		static::getLayout()->setVar( 'steps', static::$step_controllers );

		Translator::setCurrentDictionary( Translator::COMMON_DICTIONARY );
		echo static::getLayout()->render();

		exit();
	}


	/**
	 *
	 */
	protected static function initStepControllers(): void
	{

		$steps = static::$steps;

		static::$step_controllers = [];

		while( $steps ) {
			$step_name = array_shift( $steps );

			$step_base_path = static::getBasePath() . 'Step/' . $step_name . '/';
			
			require_once $step_base_path . 'Controller.php';

			$class_name = __NAMESPACE__ . '\\Installer_Step_' . $step_name . '_Controller';

			/**
			 * @var Installer_Step_CreateAdministrator_Controller $controller
			 */
			$controller = new $class_name( $step_name, $step_base_path );


			static::$step_controllers[$step_name] = $controller;

			$steps_after = $controller->getStepsAfter();
			
			foreach( $steps_after as $step_after ) {
				array_unshift( $steps, $step_after );
			}
		}

		$got_current = false;
		$current_step_name = static::getCurrentStepName();

		$steps_map = [];

		foreach( static::$step_controllers as $controller ) {
			if( !$controller->getIsAvailable() ) {
				continue;
			}

			$steps_map[] = $controller->getName();
		}

		$c = 0;
		$i = 0;
		$steps_count = count( static::$step_controllers );
		foreach( static::$step_controllers as $controller ) {
			$c++;

			if( $controller->getIsAvailable() ) {
				$is_current = ($controller->getName() == $current_step_name);
				if( $is_current ) {
					$got_current = true;
					$is_prev = false;
					$is_next = false;

					if( $i > 0 ) {
						static::$step_controllers[$steps_map[$i - 1]]->setIsPrevious( true );
					}

					if( $i <= ($steps_count - 1) ) {
						if( isset( $steps_map[$i + 1] ) ) {
							static::$step_controllers[$steps_map[$i + 1]]->setIsComing( true );
						}
					}

				} else {
					if( $got_current ) {
						$is_prev = false;
						$is_next = true;

					} else {
						$is_prev = true;
						$is_next = false;
					}
				}

				$controller->setIsCurrent( $is_current );
				$controller->setIsFuture( $is_next );
				$controller->setIsPast( $is_prev );

				$i++;
			} else {
				$controller->setIsPast( true );
			}


			$controller->setIsLast( $steps_count == $c );

		}

	}

	/**
	 * @return string
	 */
	public static function getCurrentStepName() : string
	{

		if( !static::$current_step_name ) {

			$session = static::getSession();

			$steps = array_keys( static::$step_controllers );
			$first_step = $steps[0];


			if( !$session->getValueExists( 'current_step' ) ) {
				$session->setValue( 'current_step', $first_step );
			}

			static::$current_step_name = $session->getValue( 'current_step' );
		}

		return static::$current_step_name;
	}

	/**
	 * @param string $current_step_name
	 */
	public static function setCurrentStepName( string $current_step_name ): void
	{

		static::$current_step_name = $current_step_name;
		static::getSession()->setValue( 'current_step', $current_step_name );

		static::initStepControllers();
	}

	/**
	 *
	 */
	public static function initTranslator(): void
	{

		SysConf_Jet_Translator::setAutoAppendUnknownPhrase(true);
		define('__APP_DICTIONARIES__',SysConf_Path::getDictionaries());
		SysConf_Path::setDictionaries( static::getBasePath() . 'dictionaries/' );

		Locale::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentLocale( static::getCurrentLocale() );
		Translator::setCurrentDictionary( static::getCurrentStepName() );

	}

	/**
	 * @return Installer_Step_Controller
	 */
	public static function getCurrentStepController(): Installer_Step_Controller
	{
		return static::getStepControllerInstance( static::getCurrentStepName() );
	}

	/**
	 * @param string $step_name
	 *
	 * @return Installer_Step_Controller|null
	 */
	protected static function getStepControllerInstance( string $step_name ): Installer_Step_Controller|null
	{
		if( !isset( static::$step_controllers[$step_name] ) ) {
			return null;
		}

		return static::$step_controllers[$step_name];
	}

	/**
	 * @return MVC_Layout
	 */
	public static function getLayout(): MVC_Layout
	{

		if( !static::$layout ) {
			static::$layout = Factory_MVC::getLayoutInstance( static::getBasePath() . 'layout/', 'default' );
		}

		return static::$layout;
	}

	/**
	 * @return Installer_Step_Controller|null
	 */
	public static function getPreviousController(): Installer_Step_Controller|null
	{
		foreach( static::$step_controllers as $controller ) {
			if( $controller->getIsPrevious() ) {
				return $controller;
			}
		}

		return null;
	}


	/**
	 *
	 */
	public static function goToNext(): void
	{

		static::initStepControllers();

		$coming = static::getComingController();
		if( $coming ) {
			static::setCurrentStepName( $coming->getName() );
			Http_Headers::movedTemporary( '?' );
		}

	}

	/**
	 * @return Installer_Step_Controller|null
	 */
	public static function getComingController(): Installer_Step_Controller|null
	{
		foreach( static::$step_controllers as $controller ) {
			if( $controller->getIsComing() ) {
				return $controller;
			}
		}

		return null;
	}

	/**
	 * @return MVC_View
	 */
	public static function getView(): MVC_View
	{
		return new MVC_View( static::getBasePath() . 'views/' );
	}


	/**
	 * @return string
	 */
	public static function buttonBack(): string
	{
		return static::getView()->render( 'button/back' );
	}

	/**
	 * @return string
	 */
	public static function buttonNext(): string
	{
		return static::getView()->render( 'button/next-anchor' );
	}
	
	/**
	 * @return string
	 */
	public static function buttonNextSubmit(): string
	{
		return static::getView()->render( 'button/next-submit-button' );
	}
	
	
	/**
	 * @return string
	 */
	public static function getBasePath(): string
	{
		return static::$base_path;
	}

	/**
	 * @param string $base_path
	 */
	public static function setBasePath( string $base_path ): void
	{
		static::$base_path = $base_path;
	}

	
	public static function initBases() : void
	{
		
		$URL = $_SERVER['HTTP_HOST'] . SysConf_URI::getBase();
		
		
		$web = Factory_MVC::getBaseInstance();
		$web->setName( 'Web' );
		$web->setId( Application_Web::getBaseId() );
		$default_added = false;
		foreach( Installer::getSelectedLocales() as $locale ) {
			$web_ld = $web->addLocale( $locale );
			$web_ld->setTitle( 'Web' );
			
			if(!$default_added) {
				$web_ld->setURLs( [$URL] );
				
				$default_added = true;
				
			} else {
				$web_ld->setURLs( [$URL . $locale->getLanguage().'-'.strtolower($locale->getRegion())] );
			}
			
		}
		$web->setIsDefault( true );
		$web->setIsActive( true );
		$web->setInitializer( [
			Application_Web::class,
			'init'
		] );
		$web->setInitializer( [ Application_Web::class, 'init' ] );
		$bases[$web->getId()] = $web;
		
		
		
		
		$admin = Factory_MVC::getBaseInstance();
		$admin->setIsSecret( true );
		$admin->setName( 'Admin panel' );
		$admin->setId( Application_Admin::getBaseId() );
		
		$default_added = false;
		foreach( Installer::getSelectedLocales() as $locale ) {
			$admin_ld = $admin->addLocale( $locale );
			$admin_ld->setTitle( Tr::_( 'Admin panel', [], null, $locale ) );
			$admin_ld->setURLs( [$URL . 'admin/'] );
			
			break;
		}
		
		$admin->setIsActive( true );
		$admin->setInitializer( [ Application_Admin::class, 'init'] );
		$admin->setIsSecret( true );
		$bases[$admin->getId()] = $admin;
		
		
		$rest = Factory_MVC::getBaseInstance();
		$rest->setIsSecret( true );
		$rest->setName( 'Rest' );
		$rest->setId( Application_REST::getBaseId() );
		$rest_ld = $rest->addLocale( Installer::getServicesLocale() );
		$rest_ld->setTitle( Tr::_( 'Rest API Server' ) );
		$rest_ld->setURLs( [$URL . 'rest/'] );
		$rest->setIsActive( true );
		$rest->setInitializer( [ Application_REST::class, 'init' ] );
		$rest->setIsSecret( true );
		$bases[$rest->getId()] = $rest;
		
		
		
		$services = Factory_MVC::getBaseInstance();
		$services->setIsSecret( true );
		$services->setName( 'Services' );
		$services->setId( Application_Services::getBaseId() );
		$services_ld = $services->addLocale( Installer::getServicesLocale() );
		$services_ld->setTitle( Tr::_( 'Services') );
		$services_ld->setURLs( [$URL . 'services/'] );
		$services->setIsActive( true );
		$services->setInitializer( [ Application_Services::class, 'init' ] );
		$services->setIsSecret( true );
		$bases[$services->getId()] = $services;
		
		
		
		
		$exports = Factory_MVC::getBaseInstance();
		$exports->setIsSecret( true );
		$exports->setName( 'Exports' );
		$exports->setId( Application_Exports::getBaseId() );
		$exports_ld = $exports->addLocale( Installer::getServicesLocale() );
		$exports_ld->setTitle( Tr::_( 'Exports' ) );
		$exports_ld->setURLs( [$URL . 'exports/'] );
		$exports->setIsActive( true );
		$exports->setInitializer( [ Application_Exports::class, 'init' ] );
		$exports->setIsSecret( true );
		$bases[$exports->getId()] = $exports;
		
		

		
		Installer::getSession()->setValue( 'bases', $bases );
		
	}
	
	/**
	 * @return MVC_Base[]
	 */
	public static function getBases() : array
	{
		$session = static::getSession();
		
		if( !$session->getValueExists( 'bases' ) ) {
			static::initBases();
		}
		$bases = $session->getValue( 'bases' );
		
		return $bases;
		
	}
	
}
