<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Locale;
use Jet\MVC;
use Jet\MVC_Base;
use Jet\SysConf_Path;
use Jet\Application_Service_List;

class Application_Service_Web
{
	public const GROUP = 'Web';
	
	protected static ?Application_Service_List $list = null;
	
	public static function list( ?MVC_Base $base = null, ?Locale $locale = null ): Application_Service_List
	{
		$base = $base ? : MVC::getBase();
		$locale = $locale ? : Locale::getCurrentLocale();
		
		if( !static::$list ) {
			static::$list = new Application_Service_List(
				SysConf_Path::getConfig() . 'services/' . $base->getId() . '_' . $locale . '.php',
				static::GROUP
			);
		}
		
		return static::$list;
	}
	
	
	public static function ImageManager( ?MVC_Base $base = null, ?Locale $locale = null ): null|Application_Module|Application_Service_Web_ImageManager
	{
		return static::list( $base, $locale )->get( Application_Service_Web_ImageManager::class );
	}
	
	public static function Logger( ?MVC_Base $base = null, ?Locale $locale = null ): null|Application_Module|Application_Service_Web_Logger
	{
		return static::list( $base, $locale )->get( Application_Service_Web_Logger::class );
	}
	
	public static function AuthController( ?MVC_Base $base = null, ?Locale $locale = null ): Application_Module|Application_Service_Web_Auth_Controller
	{
		return static::list( $base, $locale )->get( Application_Service_Web_Auth_Controller::class );
	}
	
	public static function AuthLoginModule( ?MVC_Base $base = null, ?Locale $locale = null ): Application_Module|Application_Service_Web_Auth_LoginModule
	{
		return static::list( $base, $locale )->get( Application_Service_Web_Auth_LoginModule::class );
	}
	
}