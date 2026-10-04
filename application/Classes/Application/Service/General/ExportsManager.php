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
	is_mandatory: false,
	name: 'Data exports',
	description: '',
	module_name_prefix: 'Exports.'
)]
abstract class Application_Service_General_ExportsManager extends Application_Module {
	
	abstract public function handleExports() : void;
	
	abstract public function getExportURL( Exports_Definition $export, ?MVC_Base_LocalizedData_Interface $base=null ) : string;

	abstract public function exportIsActive( Exports_Definition $export ) : bool;
	
	abstract public function shutdownExport( Exports_Definition $export ) : void;
	
	abstract public function startExport( Exports_Definition $export ) : void;
	
	abstract public function planExportOutage( Exports_Definition $export, ?Data_DateTime $from_date_time, ?Data_DateTime $till_date_time ) : void;
	
	abstract public function cancelPlannedOutage( Exports_Definition $export, int $plan_id ) : void;
	
}