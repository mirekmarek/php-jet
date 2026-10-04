<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplication;

use Jet\Application_Module;
use Jet\Data_DateTime;
use Jet\Application_Service_MetaInfo;
use Jet\MVC_Base_LocalizedData_Interface;

#[Application_Service_MetaInfo(
	group: Application_Service_General::GROUP,
	is_mandatory: true,
	name: 'System services',
	description: '',
	module_name_prefix: ''
)]
abstract class Application_Service_General_SysServices extends Application_Module {
	
	abstract public function handleSysServices() : void;
	
	abstract public function getSysServiceURL( SysServices_Definition $service, ?MVC_Base_LocalizedData_Interface $base=null ) : string;
	
	abstract public function serviceIsActive( SysServices_Definition $service ) : bool;
	
	abstract public function shutdownService( SysServices_Definition $service ) : void;
	
	abstract public function startService( SysServices_Definition $service ) : void;
	
	abstract public function planServiceOutage( SysServices_Definition $service, ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : void;
	
	abstract public function cancelPlannedOutage( SysServices_Definition $service, int $plan_id ) : void;
	
	
}