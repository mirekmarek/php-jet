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
use Jet\Tr;

class SysServices {
	
	
	public static function getManager() : ?Application_Service_General_SysServices
	{
		/** @phpstan-ignore return.type */
		return Application_Service_General::SysServices();
	}
	
	/**
	 * @return SysServices_Definition[]
	 */
	public static function getServiceList() : array
	{
		$list = [];
		foreach( Application_Service_List::findPossibleModules(SysServices_Provider_Interface::class) as $module) {
			/**
			 * @var SysServices_Provider_Interface&Application_Module $module
			 */
			Tr::setCurrentDictionaryTemporary(
				dictionary: $module->getModuleManifest()->getName(),
				action: function() use (&$list, $module) {
					foreach( $module->getSysServicesDefinitions() as $sys_service ) {
						$list[ $sys_service->getCode() ] = $sys_service;
					}
				}
			);
			
		}
		
		return $list;
	}
	
	public static function getService( string $code ) : ?SysServices_Definition
	{
		$list = static::getServiceList();
		
		return $list[$code]??null;
	}
	
}