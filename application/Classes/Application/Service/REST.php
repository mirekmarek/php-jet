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

class Application_Service_REST
{
	public const GROUP = 'REST';
	
	protected static ?Application_Service_List $list = null;
	
	public static function list(): Application_Service_List
	{
		if(!static::$list) {
			static::$list = new Application_Service_List(
				SysConf_Path::getConfig().'services/rest.php',
				static::GROUP
			);
		}
		
		return static::$list;
	}
	
	public static function Logger() : null|Application_Module|Application_Service_REST_Logger
	{
		return static::list()->get( Application_Service_REST_Logger::class );
	}
	
	public static function AuthController() : Application_Module|Application_Service_REST_Auth_Controller
	{
		return static::list()->get( Application_Service_REST_Auth_Controller::class );
	}
}