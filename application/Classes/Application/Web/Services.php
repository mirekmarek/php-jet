<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Services;
use Jet\MVC;
use Jet\SysConf_Path;

class Application_Web_Services extends Application_Services
{
	public const GROUP = 'Web';
	
	public static function getCfgFilePath(): string
	{
		$base = MVC::getBase();
		$locale = MVC::getLocale();
		
		return SysConf_Path::getConfig().'services/'.$base->getId().'_'.$locale.'.php';
	}
	
	public static function ImageManager() : null|Application_Module|Application_Web_Services_ImageManager
	{
		return static::get( Application_Web_Services_ImageManager::class );
	}
	
	public static function Logger() : null|Application_Module|Application_Web_Services_Logger
	{
		return static::get( Application_Web_Services_Logger::class );
	}
	
	public static function AuthController() : Application_Module|Application_Web_Services_Auth_Controller
	{
		return static::get( Application_Web_Services_Auth_Controller::class );
	}
	
	public static function AuthLoginModule() : Application_Module|Application_Web_Services_Auth_LoginModule
	{
		return static::get( Application_Web_Services_Auth_LoginModule::class );
	}
	
}