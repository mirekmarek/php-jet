<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Modules;

class Application_Admin_Services
{
	public static function ImageManager() : ?Application_Admin_Services_ImageManager
	{
		return static::findService( Application_Admin_Services_ImageManager::class );
	}
	
	protected static function findService( string $interface_name ) : mixed
	{
		$modules = Application_Modules::activatedModulesList();
		foreach($modules as $manifest) {
			$module = Application_Modules::moduleInstance( $manifest->getName() );
			
			if($module instanceof $interface_name) {
				return $module;
			}
		}
		
		return null;
	}
}