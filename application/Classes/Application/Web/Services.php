<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Modules;
use Jet\Exception;

class Application_Web_Services
{
	public static function ImageManager() : ?Application_Web_Services_ImageManager
	{
		return static::findService( Application_Web_Services_ImageManager::class );
	}
	
	public static function Logger() : ?Application_Web_Services_Logger
	{
		return static::findService( Application_Web_Services_Logger::class );
	}
	
	public static function AuthController() : Application_Web_Services_Auth_Controller
	{
		return static::findService( Application_Web_Services_Auth_Controller::class, true );
	}
	
	public static function AuthLoginModule() : Application_Web_Services_Auth_LoginModule
	{
		return static::findService( Application_Web_Services_Auth_LoginModule::class, true );
	}
	
	
	public static function findService( string $service_interface, bool $service_is_mandatory=false ) : mixed
	{
		$modules = Application_Modules::activatedModulesList();
		foreach($modules as $manifest) {
			if(!str_starts_with($manifest->getName(), 'Web.') ) {
				continue;
			}
			
			$module = Application_Modules::moduleInstance( $manifest->getName() );
			
			if($module instanceof $service_interface) {
				return $module;
			}
		}
		
		if($service_is_mandatory) {
			throw new Exception('Mandatory service '.$service_interface.' is not available');
		}
		
		return null;
	}
}