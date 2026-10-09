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


class Application_Service_General {
	
	public const GROUP = 'general';
	
	protected static ?Application_Service_List $list = null;
	
	public static function list(): Application_Service_List
	{
		if(!static::$list) {
			static::$list = new Application_Service_List(
				SysConf_Path::getConfig().'services/general.php',
				static::GROUP
			);
		}
		
		return static::$list;
	}
	
	
	public static function Exports() : Application_Service_General_ExportsManager|Application_Module|null
	{
		return static::list()->get( Application_Service_General_ExportsManager::class );
	}
	
	
	public static function SysServices() : Application_Service_General_SysServices|Application_Module|null
	{
		return static::list()->get( Application_Service_General_SysServices::class );
	}
	
	public static function OAuthManager() : OAuth_Manager|Application_Module|null
	{
		return static::list()->get( Application_Service_General_OAuth_Manager::class );
	}
}