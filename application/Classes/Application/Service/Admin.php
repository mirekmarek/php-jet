<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;
use Jet\Application_Service_List;
use Jet\SysConf_Path;

class Application_Service_Admin
{
	public const GROUP = 'Admin';
	
	protected static ?Application_Service_List $list = null;
	
	public static function getList(): Application_Service_List
	{
		if(!static::$list) {
			static::$list = new Application_Service_List(
				SysConf_Path::getConfig().'services/admin.php',
				static::GROUP
			);
		}
		
		return static::$list;
	}
	
	
	public static function ImageManager() : null|Application_Module|Application_Service_Admin_ImageManager
	{
		return static::getList()->get( Application_Service_Admin_ImageManager::class );
	}
	
	public static function AuthController() : Application_Module|Application_Service_Admin_Auth_Controller
	{
		return static::getList()->get( Application_Service_Admin_Auth_Controller::class );
	}
	
	public static function AuthLoginModule() : Application_Module|Application_Service_Admin_Auth_LoginModule
	{
		return static::getList()->get( Application_Service_Admin_Auth_LoginModule::class );
	}
	
	public static function Logger() : null|Application_Module|Application_Service_Admin_Logger
	{
		return static::getList()->get( Application_Service_Admin_Logger::class );
	}
}