<?php
/**
 *
 * @copyright 
 * @license  
 * @author  
 */
namespace JetApplicationModule\SysServices\CacheReset;

use Jet\Application_Module;
use Jet\MVC_Cache;
use JetApplication\SysServices_Provider_Interface;
use JetApplication\SysServices_Definition;

/**
 *
 */
class Main extends Application_Module implements SysServices_Provider_Interface
{
	
	public function getSysServicesDefinitions(): array
	{
		$mvc_cache = new SysServices_Definition(
			module: $this,
			name: 'MVC Cache Reset',
			description: '',
			service_code: 'mvc',
			service: function() {
				echo "Common cache ...\n";
				MVC_Cache::reset();
				echo "OK\n";
				echo "Output cache ...\n";
				MVC_Cache::resetOutputCache();
				echo "OK\n";
				
			}
		);
		$mvc_cache->setIsPeriodicallyTriggeredService( false );
		
		return [
			$mvc_cache
		];
	}
}